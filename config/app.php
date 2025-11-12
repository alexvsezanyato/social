<?php

return [
    'database' => [
        'connection' => [
            'dbname' => env('DB_NAME'),
            'user'   => env('DB_USER', 'guest'),
            'password' => env('DB_PASSWORD', ''),
            'host' => env('DB_HOST', 'localhost'),
            'driver' => env('DB_DRIVER'),
        ],
    ],
    'middleware' => [
        \App\Middlewares\ExceptionHandlerMiddleware::class,
        \App\Middlewares\ResourceNotFoundHandlerMiddleware::class,
        \App\Middlewares\SessionMiddleware::class,
        \App\Middlewares\RoutingMiddleware::class,
        \App\Middlewares\AuthMiddleware::class,
    ],
];