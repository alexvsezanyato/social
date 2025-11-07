<?php

namespace App\Http\Controllers;

use App\Services\Users;

class IndexController
{
    public function __construct(
        private Users $users,
    ) {
        if (!$users->in()) {
            header('Location: /auth/login');
            exit;
        }
    }

    public function index()
    {
        return view('index');
    }
}
