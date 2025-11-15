<?php

namespace App\Http\Controllers;

use App\Helpers\ViewInterface;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\UserService;

class ProfileController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserService $userService,
        private ViewInterface $view,
        private UserRepository $userRepository,
    ) {
    }

    public function index(Request $request)
    {
        if ($request->query->has('id')) {
            $id = (int)$request->query->get('id');
            $isOwnProfile = $this->userService->getId() === $id;
            $user = $this->userRepository->find($id);
        } else {
            $isOwnProfile = true;
            $user = $this->userService->getCurrentUser();
        }

        return new Response($this->view->render('profile/index', [
            'isOwnProfile' => $isOwnProfile,
            'user'=> $user,
        ]));
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
