<?php

namespace App\Http\Controllers;

use App\Services\Users;

class IndexController
{
    public function index()
    {
        return view('index');
    }

    public function home(Users $users)
    {
        return view('home', [
            'users' => $users,
        ]);
    }

    public function settings(Users $users)
    {
        return view('settings', [
            'users' => $users,
        ]);
    }
}
