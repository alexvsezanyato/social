<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

use App\Services\App;

App::$instance = new App(
    container: new DI\Container(require CONFIG_DIR.'/container.php'),
);

$container = App::$instance->container;
$container->set(App::class, App::$instance);

$request = Request::createFromGlobals();
$container->set(Request::class, $request);

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