<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Services\User;

class ProfileController
{
    public function index(User $user)
    {
        return new Response(view('profile/index', [
            'user' => $user,
        ]));
    }

    public function settings(User $user)
    {
        return new Response(view('profile/settings', [
            'user' => $user,
        ]));
    }

    public function logout(SessionInterface $session)
    {
        $session->start();
        $session->invalidate();
        $session->save();

        $response = new Response(status: Response::HTTP_TEMPORARY_REDIRECT, headers: [
            'location' => '/auth/login',
        ]);

        $response->headers->clearCookie('pid');

        return $response;
    }
}
