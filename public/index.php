<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Request;

use App\Services\App;

define('BASE_DIR', dirname(__DIR__));
define('RESOURCE_DIR', BASE_DIR.'/resources');
define('VIEW_DIR', RESOURCE_DIR.'/views');
define('CONFIG_DIR', BASE_DIR.'/config');
define('STORAGE_DIR', BASE_DIR.'/storage');
define('CACHE_DIR', STORAGE_DIR.'/cache');

require_once BASE_DIR.'/vendor/autoload.php';

App::$instance = new App(
    container: new DI\Container(require CONFIG_DIR.'/container.php'),
);

$container = App::$instance->container;
$container->set(App::class, App::$instance);

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(BASE_DIR.'/helpers/'),
);

foreach ($iterator as $file) {
    if ($file->getExtension() === 'php') {
        require_once $file->getPathname();
    }
}

$routes = new RouteCollection();
require BASE_DIR.'/routes/web.php';

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

[$controller, $action] = $parameters['_controller'];
echo $container->call([$container->make($controller), $action]);
