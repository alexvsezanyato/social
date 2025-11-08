<?php

use Symfony\Component\Routing\RouteCollection;
use App\Services\App;

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(BASE_DIR.'/helpers/'),
);

foreach ($iterator as $file) {
    if ($file->getExtension() === 'php') {
        require_once $file->getPathname();
    }
}

$container = new DI\Container(require CONFIG_DIR.'/container.php');

$routes = new RouteCollection();
require BASE_DIR.'/routes/web.php';

$middlewares = require CONFIG_DIR.'/middleware.php';

App::$instance = new App(
    container: $container,
    routes: $routes,
    middlewares: $middlewares,
);

$container->set(App::class, App::$instance);
return App::$instance;
