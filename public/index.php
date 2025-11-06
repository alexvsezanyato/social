<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Api\IndexController as ApiIndexController;

define('BASE_DIR', dirname(__DIR__));

require_once BASE_DIR.'/vendor/autoload.php';

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(BASE_DIR.'/helpers/'),
);

foreach ($iterator as $file) {
    if ($file->getExtension() === 'php') {
        require_once $file->getPathname();
    }
}

$routes = new RouteCollection();

$routes->add('index', new Route('/', [
    '_controller' => [IndexController::class, 'index'],
], methods: ['GET']));

$routes->add('home', new Route('/home', [
    '_controller' => [IndexController::class, 'home'],
], methods: ['GET']));

$routes->add('login', new Route('/login', [
    '_controller' => [AuthController::class, 'login'],
], methods: ['GET']));

$routes->add('api-login', new Route('/login', [
    '_controller' => [ApiAuthController::class, 'login'],
], methods: ['POST']));

$routes->add('register', new Route('/register', [
    '_controller' => [AuthController::class, 'register'],
], methods: ['GET']));

$routes->add('api-register', new Route('/register', [
    '_controller' => [ApiAuthController::class, 'register'],
], methods: ['POST']));

$routes->add('settings', new Route('/settings', [
    '_controller' => [IndexController::class, 'settings'],
], methods: ['GET']));

$routes->add('api-apply-profile', new Route('/apply-profile', [
    '_controller' => [ApiIndexController::class, 'applyProfile'],
], methods: ['POST']));

$routes->add('create-post', new Route('/create-post', [
    '_controller' => [ApiIndexController::class, 'createPost'],
], methods: ['POST']));

$routes->add('posts', new Route('/posts', [
    '_controller' => [ApiIndexController::class, 'posts'],
], methods: ['POST']));

$uri = parse_url($_SERVER['REQUEST_URI']);
$request = Request::createFromGlobals();
$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try {
    $parameters = $matcher->match($uri['path']);
} catch (ResourceNotFoundException $e) {
    http_response_code(404);
    echo $e->getMessage();
    exit;
}

$controller = new $parameters['_controller'][0];
$action = $parameters['_controller'][1];

echo $controller->$action();
