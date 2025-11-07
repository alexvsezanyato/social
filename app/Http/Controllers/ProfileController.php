<?php

namespace App\Http\Controllers;

use App\Services\Users;
use App\Services\Auth;

class ProfileController 
{
    public function index(Users $users)
    {
        return view('profile/index', [
            'users' => $users,
        ]);
    }

    public function settings(Users $users)
    {
        return view('profile/settings', [
            'users' => $users,
        ]);
    }

    public function logout(Auth $auth)
    {
        $auth->logout();
        header('Location: /auth/login');
    }
}
