<?php

namespace App\Http\Controllers;

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
        return view('login');
    }

    public function register(User $user): string
    {
        return view('register');
    }
}
