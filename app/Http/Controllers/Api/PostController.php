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
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostController
{
    public function __construct(
        private Request $request,
        private UserRepository $userRepository,
        private UserService $userService,
        private EntityManagerInterface $entityManager,
        private PostRepository $postRepository,
        private DocumentRepository $documentRepository,
        private PictureRepository $pictureRepository,
        private Paths $paths,
    ) {}

    public function create()
    {
        /**
         * @var UploadedFile[]
         */
        $documents = [];

        /**
         * @var UploadedFile[]
         */
        $pictures = [];

        $text = $this->request->request->get('text');

        if (strlen($text) < 5) {
            return new Response(content: 3);
        }

        if ($this->request->files->count() > 20) {
            return new Response(content: 4);
        }

        if (preg_match('/(\r\n|\r|\n){3,}/', $text)) {
            return new Response(content: 2);
        }

        foreach ($this->request->files->all() as $k => $file) {
            if ($k[0] === 'd') {
                $documents[] = $file;
            } elseif ($k[0] === 'p') {
                $pictures[] = $file;
            }
        }

        if (count($pictures) > 9 || count($documents) > 5) {
            return new Response(content: 5);
        }

        $this->entityManager->beginTransaction();

        $post = new Post();
        $post->authorId = $this->userService->getCurrentUser()->id;
        $post->text = htmlspecialchars($text);
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $documentUploadDirectory = $this->paths->upload.'/documents';
        $pictureUploadDirectory = $this->paths->upload.'/pictures';

        foreach ($documents as $i => $file) {
            if (!$file->isValid()) {
                $this->entityManager->rollback();
                return new Response(content: $file->getErrorMessage());
            }

            $documentFileName = $post->id . $i;

            if (file_exists($documentUploadDirectory.'/'.$documentFileName)) {
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
            $document->pid = $post->id;
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

            if (file_exists($pictureUploadDirectory.'/'.$pictureFileName)) {
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
            $picture->pid = $post->id;
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

    public function remove()
    {
        $postId = $this->request->getContent();
        $post = $this->postRepository->find($postId);

        if (!$post) { 
            return new Response(content: 2);
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
            return new Response(content: 2);
        }

        return new Response(content: 0);
    }

    public function posts()
    {
        $json = json_decode($this->request->getContent());

        if (!$json) {
            return new Response(content: json_encode([
                'code' => 1,
            ]));
        }

        if (!isset($json->from) || !isset($json->limit)) {
            return new Response(content: json_encode([
                'code' => 2,
            ]));
        }

        $from = $json->from ?? 0;

        if (!is_numeric($from)) {
            return new Response(content: json_encode([
                'code' => 2,
            ]));
        } 

        $result = [];

        try {
            $posts = $this->postRepository->findWithPagination(
                offset: $from,
                limit: 10,
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
        }

        $user = $this->userService->getCurrentUser();

        return new Response(json_encode([
            'code' => 0,
            'posts' => array_values($result),

            'user' => [
                'id' => $user->id,
                'public' => $user->public,
            ]
        ]));
    }
}
