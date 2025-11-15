<?php

namespace App\Http\Controllers\Api;

use App\Entities\PostComment;
use App\Repositories\PostCommentRepository;
use App\Repositories\PostRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
        $postId = (int)$request->request->get('post_id');
        $text = $request->request->get('text');

        $comment = new PostComment();
        $comment->author = $user;
        $comment->post = $this->postRepository->find($postId);
        $comment->text = $text;
        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return new JsonResponse([
            'status' => 'success',
            'comment_id' => $comment->id,
        ]);
    }

    public function delete(Request $request)
    {
        $id = $request->query->get('id');
        $comment = $this->postCommentRepository->find($id);
        $comment->delete();
        $this->entityManager->flush();
    }

    public function get(Request $request)
    {
        $id = $request->query->get('id');
        $comment = $this->postCommentRepository->find($id);

        return new JsonResponse([
            'id' => $comment->id,
            'author_id' => $comment->authorId,
            'post_id' => $comment->postId,
            'text' => $comment->text,
        ]);
    }
}
