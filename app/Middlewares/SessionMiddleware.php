<?php

namespace App\Middlewares;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private SessionInterface $session,
    ) {
    }

    public function process(Request $request, callable $handler): Response
    {
        $this->session->start();
        $response = $handler($request);
        $this->session->save();

        return $response;
    }
}
