<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function index()
    {
        return new Response(view('index'));
    }
}
