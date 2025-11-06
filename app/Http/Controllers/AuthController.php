<?php

namespace App\Http\Controllers;

class AuthController
{
    public function login()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }
}