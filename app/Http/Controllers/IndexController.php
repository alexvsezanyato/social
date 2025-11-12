<?php

namespace App\Http\Controllers;

use App\Helpers\ViewInterface;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function __construct(
        private ViewInterface $view,
    ) {
    }

    public function index()
    {
        return new Response($this->view->render('index'));
    }
}
