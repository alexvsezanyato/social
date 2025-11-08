<?php

namespace App\Http\Controllers;

use App\Services\User;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function __construct(
        private User $user,
    ) {
        if (!$user->in()) {
            header('Location: /auth/login');
            exit;
        }
    }

    public function index()
    {
        return new Response(view('index'));
    }
}
