<?php

namespace App\Middlewares;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Services\User;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private User $user
    ) {}

    public function process(Request $request, callable $handler): Response
    {
        $parameters = $request->attributes->get('parameters', []);
        $tags = $parameters['tags'] ?? [];
        $isAuthenticationRoute = in_array('auth', $tags, true);
        $isLoggedIn = $this->user->in();

        if (!$isLoggedIn && !$isAuthenticationRoute) {
            return new Response(status: Response::HTTP_TEMPORARY_REDIRECT, headers: [
                'location' => '/auth/login',
            ]);
        }

        if ($isLoggedIn && $isAuthenticationRoute) {
            return new Response(status: Response::HTTP_TEMPORARY_REDIRECT, headers: [
                'location' => '/',
            ]);
        }

        return $handler($request);
    }
}