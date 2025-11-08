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
}
