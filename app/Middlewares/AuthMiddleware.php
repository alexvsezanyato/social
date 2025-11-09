<?php

namespace App\Middlewares;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Services\UserService;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private UserService $userService,
    ) {}

    public function process(Request $request, callable $handler): Response
    {
        $parameters = $request->attributes->get('parameters', []);
        $tags = $parameters['tags'] ?? [];
        $isAuthenticationRoute = in_array('auth', $tags, true);
        $isAuthenticated = $this->userService->isAuthenticated();

        if (!$isAuthenticated && !$isAuthenticationRoute) {
            return new Response(status: Response::HTTP_TEMPORARY_REDIRECT, headers: [
                'location' => '/auth/login',
            ]);
        }

        if ($isAuthenticated && $isAuthenticationRoute) {
            return new Response(status: Response::HTTP_TEMPORARY_REDIRECT, headers: [
                'location' => '/',
            ]);
        }

        return $handler($request);
    }
}