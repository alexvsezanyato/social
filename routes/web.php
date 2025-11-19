<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PostCommentController;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

use App\Http\Controllers\IndexController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\ProfileController as ApiProfileController;
use App\Http\Controllers\Api\PostController as ApiPostController;
use App\Http\Controllers\Api\PostCommentController as ApiPostCommentController;

/**
 * @var RouteCollection $routes
 */

$routes->add('api.auth.login', new Route(
    path: '/api/auth/login',
    defaults: [
        '_controller' => [ApiAuthController::class, 'login'],
        'tags' => ['auth'],
    ],
    methods: [
        'POST',
    ],
));

$routes->add('api.auth.register', new Route(
    path: '/api/auth/register',
    defaults: [
        '_controller' => [ApiAuthController::class, 'register'],
        'tags' => ['auth'],
    ],
    methods: [
        'POST',
    ],
));

$routes->add('auth.login', new Route(
    path: '/auth/login',
    defaults: [
        '_controller' => [AuthController::class, 'login'],
        'tags' => ['auth'],
    ],
    methods: [
        'GET',
    ],
));

$routes->add('auth.register', new Route(
    path: '/auth/register',
    defaults: [
        '_controller' => [AuthController::class, 'register'],
        'tags' => ['auth'],
    ],
    methods: [
        'GET',
    ],
));

$routes->add('index', new Route(
    path: '/',
    defaults: [
        '_controller' => [IndexController::class, 'index'],
    ],
    methods: [
        'GET',
    ],
));

$routes->add('profile.index', new Route(
    path: '/profile/index',
    defaults: [
        '_controller' => [ProfileController::class, 'index'],
    ],
    methods: [
        'GET',
    ],
));

$routes->add('profile.settings', new Route(
    path: '/profile/settings',
    defaults: [
        '_controller' => [ProfileController::class, 'settings'],
    ],
    methods: [
        'GET',
    ],
));

$routes->add('profile.logout', new Route(
    path: '/profile/logout',
    defaults: [
        '_controller' => [ProfileController::class, 'logout'],
    ],
    methods: [
        'GET',
    ],
));

$routes->add('api.profile.apply', new Route(
    path: '/api/profile/apply',
    defaults: [
        '_controller' => [ApiProfileController::class, 'apply'],
    ],
    methods: [
        'POST',
    ],
));

$routes->add('api.profile.get', new Route(
    path: '/api/profile/get',
    defaults: [
        '_controller' => [ApiProfileController::class, 'get'],
    ],
    methods: [
        'GET',
    ],
));

$routes->add('api.post.create', new Route(
    path: '/api/post/create',
    defaults: [
        '_controller' => [ApiPostController::class, 'create'],
    ],
    methods: [
        'POST',
    ],
));

$routes->add('api.posts', new Route(
    path: '/api/posts',
    defaults: [
        '_controller' => [ApiPostController::class, 'posts'],
    ],
    methods: [
        'GET',
    ],
));

$routes->add('api.post.get', new Route(
    path: '/api/post',
    defaults: [
        '_controller' => [ApiPostController::class, 'get'],
    ],
    methods: [
        'GET',
    ],
));

$routes->add('api.post.delete', new Route(
    path: '/api/post/delete',
    defaults: [
        '_controller' => [ApiPostController::class, 'delete'],
    ],
    methods: [
        'GET',
    ],
));

$routes->add('document.download', new Route(
    path: '/document/download',
    defaults: [
        '_controller' => [DocumentController::class, 'download'],
    ],
    methods: [
        'GET',
    ],
));

$routes->add('api.post-comment.create', new Route(
    path: '/api/post-comment/create',
    defaults: [
        '_controller' => [ApiPostCommentController::class, 'create'],
    ],
    methods: [
        'POST',
    ],
));

$routes->add('api.post-comment.delete', new Route(
    path: '/api/post-comment/delete',
    defaults: [
        '_controller' => [ApiPostCommentController::class, 'delete'],
    ],
    methods: [
        'GET',
    ],
));

$routes->add('post-comment.index', new Route(
    path: '/post-comment/index',
    defaults: [
        '_controller' => [PostCommentController::class, 'index'],
    ],
    methods: [
        'GET',
    ],
));