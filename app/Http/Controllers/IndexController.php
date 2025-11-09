<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function index(\DI\Container $container)
    {
        return new Response(view('index'));
    }
}
