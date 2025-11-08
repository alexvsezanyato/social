<?php

namespace App\Http\Controllers;

use App\Services\Auth;
use App\Services\User;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    public function login(User $user)
    {
        return new Response(view('auth/login'));
    }

    public function register(User $user)
    {
        return new Response(view('auth/register'));
    }

    public function logout(Auth $auth)
    {
        $auth->logout();

        return new Response('', Response::HTTP_TEMPORARY_REDIRECT, [
            'location' => '/auth/login',
        ]);
    }
}
