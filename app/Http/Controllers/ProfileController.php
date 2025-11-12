<?php

namespace App\Http\Controllers;

use App\Helpers\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\UserService;

class ProfileController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserService $userService,
        private View $view,
    ) {}

    public function index()
    {
        return new Response($this->view->render('profile/index'));
    }

    public function settings()
    {
        return new Response($this->view->render('profile/settings'));
    }

    public function logout(SessionInterface $session)
    {
        $session->invalidate();

        $response = new Response(status: Response::HTTP_TEMPORARY_REDIRECT, headers: [
            'location' => '/auth/login',
        ]);

        $response->headers->clearCookie('pid');

        return $response;
    }
}
