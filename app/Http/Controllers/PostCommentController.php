<?php

namespace App\Http\Controllers;

use App\Helpers\ViewInterface;
use App\Repositories\PostCommentRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\UserService;

class PostCommentController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserService $userService,
        private ViewInterface $view,
        private UserRepository $userRepository,
        private PostRepository $postRepository,
        private PostCommentRepository $postCommentRepository,
    ) {
    }

    public function index(Request $request)
    {
        $id = $request->query->get('id');
        $comment = $this->postCommentRepository->find($id);

        return new Response($this->view->render('/components/post/comment', [
            'comment' => $comment,
        ]));
    }
}
