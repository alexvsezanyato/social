<?php

namespace App\Http\Controllers;

class IndexController
{
    public function index()
    {
        return view('index');
    }

    public function home()
    {
        return view('home');
    }

    public function settings()
    {
        return view('settings');
    }
}
