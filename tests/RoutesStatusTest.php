<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Services\App;

beforeAll(function () {
    define('BASE_DIR', dirname(__DIR__));
    App::$instance = require BASE_DIR.'/bootstrap/app.php';
});

it('returns 200', function ($path, $method) {
    $app = App::$instance;

    $request = Request::create($path, $method);
    $response = $app->handleRequest($request);

    expect($response->getStatusCode())->toBe(Response::HTTP_OK);
})->with([
    ['/auth/login', 'GET'],
    ['/auth/register', 'GET'],
]);

it('returns 200 (/api/auth/login)', function () {
    $app = App::$instance;

    $request = Request::create('/api/auth/login',  'POST', [
        'login' => 'test1057243321',
        'password' => 'O3qYFy76@H-3',
    ]);

    $response = $app->handleRequest($request);

    expect($response->getStatusCode())->toBe(Response::HTTP_OK);
});