<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entities\User;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private SessionInterface $session,
        private Request $request,
        private UserRepository $userRepository,
    ) {
    }

    public function isAuthenticated(): bool
    {
        $server = $this->request->server;
        $cookies = $this->request->cookies;

        $id = $this->session->get('id');
        $hash = $this->session->get('hash');

        $userAgent = $server->get('HTTP_USER_AGENT', 'agent');

        if ($id !== null && $hash === hash('md5', $userAgent)) {
            return true;
        }

        if (!$cookies->has('pid')) {
            return false;
        }

        [$id, $hash] = explode('-', $cookies->get('pid'));
        $user = $this->userRepository->find($id);

        if ($id !== null && $hash === hash('sha256', $id . $user->random)) {
            return true;
        }

        return false;
    }

    public function getId(): ?int
    {
        $cookies = $this->request->cookies;

        $id = $this->session->get('id');

        if ($id !== null) {
            return $id;
        }

        if (!$cookies->has('pid')) {
            return null;
        }

        [$id] = explode('-', $cookies->get('pid'));

        if (!is_numeric($id)) {
            throw new \Exception('Invalid user ID in auth cookie');
        }

        return (int)$id;
    }

    public function getCurrentUser(): ?User
    {
        $id = $this->getId();
        return $id ? $this->userRepository->find($id) : null;
    }
}
