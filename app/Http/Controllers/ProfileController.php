<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use App\Services\User;
use App\Services\Auth;

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

    public function logout(Auth $auth)
    {
        $auth->logout();

        return new Response('', Response::HTTP_TEMPORARY_REDIRECT, [
            'location' => '/auth/login',
        ]);
    }
}
