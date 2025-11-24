<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PostCommentController;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

use App\Http\Controllers\IndexController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Controllers\Api\PostController as ApiPostController;
use App\Http\Controllers\Api\PostCommentController as ApiPostCommentController;
use App\Http\Controllers\Api\RecommendedPostController as ApiRecommendedPostController;

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

$routes->add('auth.logout', new Route(
    path: '/auth/logout',
    defaults: [
        '_controller' => [AuthController::class, 'logout'],
    ],
    methods: [
        'GET',
    ],
));

$routes->add('api.user.patch', new Route(
    path: '/api/users/{id}',
    defaults: [
        '_controller' => [ApiUserController::class, 'patch'],
    ],
    requirements: [
        'id' => '\d+',
    ],
    methods: [
        'PATCH',
    ],
));

$routes->add('api.user.show', new Route(
    path: '/api/users/{id}',
    defaults: [
        '_controller' => [ApiUserController::class, 'show'],
    ],
    requirements: [
        'id' => '\d+',
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

$routes->add('api.post.create', new Route(
    path: '/api/posts',
    defaults: [
        '_controller' => [ApiPostController::class, 'create'],
    ],
    methods: [
        'POST',
    ],
));

$routes->add('api.post.index', new Route(
    path: '/api/posts',
    defaults: [
        '_controller' => [ApiPostController::class, 'index'],
    ],
    methods: [
        'GET',
    ],
));

$routes->add('api.post.show', new Route(
    path: '/api/posts/{id}',
    defaults: [
        '_controller' => [ApiPostController::class, 'show'],
    ],
    requirements: [
        'id' => '\d+',
    ],
    methods: [
        'GET',
    ],
));

$routes->add('api.post.delete', new Route(
    path: '/api/posts/{id}',
    defaults: [
        '_controller' => [ApiPostController::class, 'delete'],
    ],
    requirements: [
        'id' => '\d+',
    ],
    methods: [
        'DELETE',
    ],
));

$routes->add('api.recommended-post.index', new Route(
    path: '/api/recommended-posts',
    defaults: [
        '_controller' => [ApiRecommendedPostController::class, 'index'],
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
    path: '/api/post-comments',
    defaults: [
        '_controller' => [ApiPostCommentController::class, 'create'],
    ],
    methods: [
        'POST',
    ],
));

$routes->add('api.post-comment.delete', new Route(
    path: '/api/post-comments/{id}',
    defaults: [
        '_controller' => [ApiPostCommentController::class, 'delete'],
    ],
    requirements: [
        'id' => '\d+',
    ],
    methods: [
        'DELETE',
    ],
));

$routes->add('api.post-comment.show', new Route(
    path: '/api/post-comments/{id}',
    defaults: [
        '_controller' => [ApiPostCommentController::class, 'show'],
    ],
    requirements: [
        'id' => '\d+',
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