<?php

namespace App\Http\Controllers;

use App\Helpers\ViewInterface;
use App\Repositories\PostRepository;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function __construct(
        private ViewInterface $view,
        private PostRepository $postRepository,
    ) {
    }

    public function index()
    {
        return new Response($this->view->render('index', [
            'posts' => $this->postRepository->findRecommended(),
        ]));
    }
}
