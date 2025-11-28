<?php

namespace App\Http\Controllers\Api;

use App\Entities\PostComment;
use App\Repositories\PostCommentRepository;
use App\Repositories\PostRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostCommentController
{
    public function __construct(
        private UserService $userService,
        private EntityManagerInterface $entityManager,
        private PostCommentRepository $postCommentRepository,
        private PostRepository $postRepository,
    ) {
    }

    public function create(Request $request)
    {
        $data = $request->toArray();

        $user = $this->userService->getCurrentUser();
        $postId = (int)$data['postId'];
        $text = $data['text'];

        $comment = new PostComment();
        $comment->author = $user;
        $comment->post = $this->postRepository->find($postId);
        $comment->text = $text;
        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return new Response($comment->id);
    }

    public function delete(int $id)
    {
        $comment = $this->postCommentRepository->find($id);

        if (!$comment) {
            return new Response(status: Response::HTTP_NOT_FOUND);
        }

        if ($comment->author->id !== $this->userService->getId()) {
            return new Response(status: Response::HTTP_FORBIDDEN);
        }

        $this->entityManager->remove($comment);
        $this->entityManager->flush();
        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    public function show(int $id)
    {
        $comment = $this->postCommentRepository->find($id);

        return new JsonResponse([
            'id' => $comment->id,
            'author' => [
                'id' => $comment->author->id,
                'login' => $comment->author->login,
                'age' => $comment->author->age,
                'public' => $comment->author->public,
            ],
            'postId' => $comment->post->id,
            'text' => $comment->text,
        ]);
    }
}
