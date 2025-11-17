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

        if (strlen($text) < 5) {
            return new Response(content: 3);
        }

        if ($request->files->count() > 20) {
            return new Response(content: 4);
        }

        if (preg_match('/(\r\n|\r|\n){3,}/', $text)) {
            return new Response(content: 2);
        }

        $documents = $request->files->all('documents') ?: [];
        $pictures = $request->files->all('pictures') ?: [];

        if (count($pictures) > 9 || count($documents) > 5) {
            return new Response(content: 5);
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
                return new Response(content: $file->getErrorMessage());
            }

            $documentFileName = $post->id . $i;

            if (file_exists($documentUploadDirectory . '/' . $documentFileName)) {
                $this->entityManager->rollback();
                return new Response(content: 7);
            }

            $documentOriginalName = basename($file->getClientOriginalName());

            if (strlen($documentOriginalName) > 64) {
                $this->entityManager->rollback();
                return new Response(content: 6);
            }

            try {
                $file = $file->move($documentUploadDirectory, $documentFileName);
            } catch (FileException $e) {
                $this->entityManager->rollback();
                return new Response(content: 9);
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
                return new Response(content: $file->getErrorMessage());
            }

            $pictureFileName = $post->id . $i;

            if (file_exists($pictureUploadDirectory . '/' . $pictureFileName)) {
                $this->entityManager->rollback();
                return new Response(content: 7);
            }

            $pictureOriginalName = basename($file->getClientOriginalName());

            if (strlen($pictureOriginalName) > 64) {
                $this->entityManager->rollback();
                return new Response(content: 6);
            }

            try {
                $file = $file->move($pictureUploadDirectory, $pictureFileName);
            } catch (FileException $e) {
                $this->entityManager->rollback();
                return new Response(content: 9);
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
            return new Response(content: $e->getMessage());
        }

        return new Response(content: 0);
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
        $this->entityManager->remove($post);

        $documents = $this->documentRepository->findBy([
            'pid' => $postId,
        ]);

        foreach ($documents as $document) {
            $this->entityManager->remove($document);
        }

        $pictures = $this->pictureRepository->findBy([
            'pid' => $postId,
        ]);

        foreach ($pictures as $picture) {
            $this->entityManager->remove($picture);
        }

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
        $author = $this->userRepository->find($authorId);

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
            $date = $post->createdAt->format('Y-m-d');
            $time = $post->createdAt->format('H:i:s');

            $result[$post->id] = [
                'id' => $post->id,
                'text' => $post->text,
                'author_id' => $post->authorId,
                'date' => $date,
                'time' => $time,
                'pics' => [],
                'docs' => [],
                'comments' => [],
            ];

            $documents = $this->documentRepository->findBy([
                'pid' => $post->id,
            ]);

            foreach ($documents as $document) {
                $result[$post->id]['docs'][] = [
                    'id' => $document->id,
                    'pid' => $document->pid,
                    'name' => $document->name,
                    'mime' => $document->mime,
                    'source' => $document->source,
                ];
            }

            $pictures = $this->pictureRepository->findBy([
                'pid' => $post->id,
            ]);

            foreach ($pictures as $picture) {
                $result[$post->id]['pics'][] = [
                    'id' => $picture->id,
                    'pid' => $picture->pid,
                    'name' => $picture->name,
                    'mime' => $picture->mime,
                    'source' => $picture->source,
                ];
            }

            $comments = $this->postCommentRepository->findBy([
                'postId' => $post->id,
            ]);

            foreach ($comments as $comment) {
                $commentAuthor = $this->userRepository->find($comment->authorId);

                $result[$post->id]['comments'][] = [
                    'id' => $comment->id,
                    'author' => [
                        'id' => $commentAuthor->id,
                        'public' => $commentAuthor->public,
                    ],
                    'text' => $comment->text,
                ];
            }
        }

        return new Response(json_encode([
            'code' => 0,
            'posts' => array_values($result),

            'user' => [
                'id' => $author->id,
                'public' => $author->public,
            ]
        ]));
    }
}
