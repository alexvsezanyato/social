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
        $user = $this->userService->getCurrentUser();
        $postId = (int)$request->request->get('postId');
        $text = $request->request->get('text');

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
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
        return new Response();
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
