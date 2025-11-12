<?php

namespace App\Http\Controllers;

use App\Helpers\View;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function __construct(
        private View $view,
    ) {}

    public function index()
    {
        return new Response($this->view->render('index'));
    }
}
