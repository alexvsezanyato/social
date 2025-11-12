<?php

namespace App\Http\Controllers;

use App\Helpers\ViewInterface;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    public function __construct(
        private ViewInterface $view,
    ) {
    }

    public function login()
    {
        return new Response($this->view->render('auth/login'));
    }

    public function register()
    {
        return new Response($this->view->render('auth/register'));
    }
}
