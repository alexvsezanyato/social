<?php

namespace App\Http\Controllers\Api;

use App\Support\Paths;
use App\Entities\Document;
use App\Entities\Picture;
use App\Entities\Post;
use App\Repositories\DocumentRepository;
use App\Repositories\PictureRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use App\Repositories\PostCommentRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostController
{
    public function __construct(
        private UserRepository $userRepository,
        private UserService $userService,
        private EntityManagerInterface $entityManager,
        private PostRepository $postRepository,
        private DocumentRepository $documentRepository,
        private PictureRepository $pictureRepository,
        private Paths $paths,
        private PostCommentRepository $postCommentRepository,
    ) {
    }

    public function create(Request $request)
    {
        /**
         * @var UploadedFile[]
         */
        $documents = [];

        /**
         * @var UploadedFile[]
         */
        $pictures = [];

        $text = $request->request->get('text');

        if (strlen($text) === 0) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        if ($request->files->count() > 20) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        if (preg_match('/(\r\n|\r|\n){3,}/', $text)) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        $documents = $request->files->all('documents') ?: [];
        $pictures = $request->files->all('pictures') ?: [];

        if (count($pictures) > 9 || count($documents) > 5) {
            return new Response(status: Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->beginTransaction();

        $post = new Post();
        $post->author = $this->userService->getCurrentUser();
        $post->text = htmlspecialchars($text);
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $documentUploadDirectory = $this->paths->upload . '/documents';
        $pictureUploadDirectory = $this->paths->upload . '/pictures';

        foreach ($documents as $i => $file) {
            if (!$file->isValid()) {
                $this->entityManager->rollback();
                return new Response(content: $file->getErrorMessage(), status: Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $documentFileName = $post->id . $i;

            if (file_exists($documentUploadDirectory . '/' . $documentFileName)) {
                $this->entityManager->rollback();
                return new Response(status: Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $documentOriginalName = basename($file->getClientOriginalName());

            if (strlen($documentOriginalName) > 64) {
                $this->entityManager->rollback();
                return new Response(status: Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            try {
                $file = $file->move($documentUploadDirectory, $documentFileName);
            } catch (FileException $e) {
                $this->entityManager->rollback();
                return new Response(content: $e->getMessage(), status: Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $document = new Document();
            $document->post = $post;
            $document->source = "documents/$documentFileName";
            $document->mime = $file->getMimeType();
            $document->name = $documentOriginalName;
            $this->entityManager->persist($document);
        }

        foreach ($pictures as $i => $file) {
            if (!$file->isValid()) {
                $this->entityManager->rollback();
                return new Response(content: $file->getErrorMessage(), status: Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $pictureFileName = $post->id . $i;

            if (file_exists($pictureUploadDirectory . '/' . $pictureFileName)) {
                $this->entityManager->rollback();
                return new Response(status: Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $pictureOriginalName = basename($file->getClientOriginalName());

            if (strlen($pictureOriginalName) > 64) {
                $this->entityManager->rollback();
                return new Response(status: Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            try {
                $file = $file->move($pictureUploadDirectory, $pictureFileName);
            } catch (FileException $e) {
                $this->entityManager->rollback();
                return new Response(content: $e->getMessage(), status: Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $picture = new Picture();
            $picture->post = $post;
            $picture->source = $pictureFileName;
            $picture->mime = $file->getMimeType();
            $picture->name = $pictureOriginalName;
            $this->entityManager->persist($picture);
        }

        try {
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            return new Response(content: $e->getMessage(), status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response(content: $post->id, status: Response::HTTP_CREATED);
    }

    public function delete(Request $request)
    {
        $postId = $request->query->get('id');
        $post = $this->postRepository->find($postId);

        if ($post->authorId !== $this->userService->getId()) {
            return new JsonResponse([
                'status' => 'fail',
                'error' => 'Permission denied',
            ]);
        }

        if (!$post) {
            return new JsonResponse([
                'status' => 'fail',
                'error'=> 'Post not found',
            ]);
        }

        $this->entityManager->beginTransaction();

        foreach ($post->documents as $document) {
            $this->entityManager->remove($document);
        }

        foreach ($post->pictures as $picture) {
            $this->entityManager->remove($picture);
        }

        $this->entityManager->remove($post);

        try {
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'fail',
                'error' => $e->getMessage(),
            ]);
        }

        return new JsonResponse([
            'status' => 'success',
        ]);
    }

    public function posts(Request $request)
    {
        $authorId = (int)$request->query->get('user_id', $this->userService->getId());

        if (!$request->query->has('from') || !$request->query->has('limit')) {
            return new JsonResponse([
                'code' => 2,
            ]);
        }

        $from = $request->query->get('from');
        $limit = $request->query->get('limit');

        if (!is_numeric($from)) {
            return new JsonResponse([
                'code' => 2,
            ]);
        }

        $result = [];

        try {
            $posts = $this->postRepository->findWithPagination(
                offset: $from,
                limit: $limit,
                authorId: $authorId,
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }

        foreach ($posts as $post) {
            $result[$post->id] = [
                'id' => $post->id,
                'text' => $post->text,
                'author' => [
                    'id' => $post->author->id,
                    'public' => $post->author->public,
                ],
                'createdAt' => [
                    'date' => $post->createdAt->format('Y-m-d'),
                    'time' => $post->createdAt->format('H:i:s'),
                ],
                'pictures' => [],
                'documents' => [],
                'comments' => [],
            ];

            foreach ($post->documents as $document) {
                $result[$post->id]['documents'][] = [
                    'id' => $document->id,
                    'pid' => $document->post->id,
                    'name' => $document->name,
                    'mime' => $document->mime,
                    'source' => $document->source,
                ];
            }

            foreach ($post->pictures as $picture) {
                $result[$post->id]['pictures'][] = [
                    'id' => $picture->id,
                    'pid' => $picture->post->id,
                    'name' => $picture->name,
                    'mime' => $picture->mime,
                    'source' => $picture->source,
                ];
            }

            foreach ($post->comments as $comment) {
                $result[$post->id]['comments'][] = [
                    'id' => $comment->id,
                    'author' => [
                        'id' => $comment->author->id,
                        'public' => $comment->author->public,
                    ],
                    'text' => $comment->text,
                ];
            }
        }

        return new Response(json_encode(array_values($result)));
    }

    public function get(Request $request)
    {
        $post = $this->postRepository->find($request->query->get('id'));

        $result = [
            'id' => $post->id,
            'text' => $post->text,
            'author' => [
                'id' => $post->author->id,
                'public' => $post->author->public,
            ],
            'createdAt' => [
                'date' => $post->createdAt->format('Y-m-d'),
                'time' => $post->createdAt->format('H:i:s'),
            ],
            'pictures' => [],
            'documents' => [],
            'comments' => [],
        ];

        foreach ($post->documents as $document) {
            $result['documents'][] = [
                'id' => $document->id,
                'pid' => $document->post->id,
                'name' => $document->name,
                'mime' => $document->mime,
                'source' => $document->source,
            ];
        }

        foreach ($post->pictures as $picture) {
            $result['pictures'][] = [
                'id' => $picture->id,
                'pid' => $picture->post->id,
                'name' => $picture->name,
                'mime' => $picture->mime,
                'source' => $picture->source,
            ];
        }

        foreach ($post->comments as $comment) {
            $result[$post->id]['comments'][] = [
                'id' => $comment->id,
                'author' => [
                    'id' => $comment->author->id,
                    'public' => $comment->author->public,
                ],
                'text' => $comment->text,
            ];
        }

        return new JsonResponse($result);
    }
}
