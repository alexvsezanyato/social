<?php

use App\Helpers\Env;

return [
    'database' => [
        'connection' => [
            'dbname'   => Env::get('DB_NAME'),
            'user'     => Env::get('DB_USER', 'guest'),
            'password' => Env::get('DB_PASSWORD', ''),
            'host'     => Env::get('DB_HOST', 'localhost'),
            'driver'   => Env::get('DB_DRIVER'),
        ],
    ],
    'middleware' => [
        \App\Middlewares\ExceptionHandlerMiddleware::class,
        \App\Middlewares\ResourceNotFoundHandlerMiddleware::class,
        \App\Middlewares\SessionMiddleware::class,
        \App\Middlewares\RoutingMiddleware::class,
        \App\Middlewares\AuthMiddleware::class,
    ],
    'environment' => Env::get('ENVIRONMENT'),
    'url' => Env::get('URL'),
];