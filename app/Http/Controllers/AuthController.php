<?php

namespace App\Http\Controllers;

use App\Services\Auth;
use App\Services\User;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    public function __construct(User $user) {
        if ($user->in()) {
            header("Location: /");
            exit;
        }
    }

    public function login(User $user)
    {
        return new Response(view('auth/login'));
    }

    public function register(User $user): string
    {
        return new Response(view('auth/register'));
    }

    public function logout(Auth $auth)
    {
        $auth->logout();
        header('Location: /auth/login');
        return new Response(0);
    }
}
