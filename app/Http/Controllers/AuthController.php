<?php

namespace App\Http\Controllers;

use App\Services\Auth;
use App\Services\User;

class AuthController
{
    public function __construct(User $user) {
        if ($user->in()) {
            header("Location: /");
            exit;
        }
    }

    public function login(User $user): string
    {
        return view('auth/login');
    }

    public function register(User $user): string
    {
        return view('auth/register');
    }

    public function logout(Auth $auth)
    {
        $auth->logout();
        header('Location: /auth/login');
    }
}
