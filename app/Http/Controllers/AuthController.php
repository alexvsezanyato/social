<?php

namespace App\Http\Controllers;

use App\Helpers\ViewInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthController
{
    public function __construct(
        private ViewInterface $view,
        private SessionInterface $session,
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

    public function logout()
    {
        $this->session->invalidate();

        $response = new Response(status: Response::HTTP_TEMPORARY_REDIRECT, headers: [
            'location' => '/auth/login',
        ]);

        $response->headers->clearCookie('pid');

        return $response;
    }
}
