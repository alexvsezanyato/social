<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

use App\Http\Controllers\IndexController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\ProfileController as ApiProfileController;
use App\Http\Controllers\Api\PostController as ApiPostController;

/**
 * @var RouteCollection $routes
 */

$routes->add('index', new Route('/', [
    '_controller' => [IndexController::class, 'index'],
], methods: ['GET']));

$routes->add('profile.index', new Route('/profile/index', [
    '_controller' => [ProfileController::class, 'index'],
], methods: ['GET']));

$routes->add('profile.settings', new Route('/profile/settings', [
    '_controller' => [ProfileController::class, 'settings'],
], methods: ['GET']));

$routes->add('auth.login', new Route('/auth/login', [
    '_controller' => [AuthController::class, 'login'],
], methods: ['GET']));

$routes->add('profile.logout', new Route('/profile/logout', [
    '_controller' => [ProfileController::class, 'logout'],
], methods: ['GET']));

$routes->add('auth.register', new Route('/auth/register', [
    '_controller' => [AuthController::class, 'register'],
], methods: ['GET']));

$routes->add('api.auth.login', new Route('/api/auth/login', [
    '_controller' => [ApiAuthController::class, 'login'],
], methods: ['POST']));

$routes->add('api.auth.logout', new Route('/api/auth/logout', [
    '_controller' => [ApiAuthController::class, 'logout'],
], methods: ['POST']));

$routes->add('api.auth.register', new Route('/api/auth/register', [
    '_controller' => [ApiAuthController::class, 'register'],
], methods: ['POST']));

$routes->add('api.profile.apply', new Route('/api/profile/apply', [
    '_controller' => [ApiProfileController::class, 'apply'],
], methods: ['POST']));

$routes->add('api.post.create', new Route('/api/post/create', [
    '_controller' => [ApiPostController::class, 'create'],
], methods: ['POST']));

$routes->add('api.posts', new Route('/posts', [
    '_controller' => [ApiPostController::class, 'posts'],
], methods: ['POST']));

$routes->add('api.post.remove', new Route('/api/post/remove', [
    '_controller' => [ApiPostController::class, 'remove'],
], methods: ['POST']));