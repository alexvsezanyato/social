<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    public function login()
    {
        return new Response(view('auth/login'));
    }

    public function register()
    {
        return new Response(view('auth/register'));
    }
}
