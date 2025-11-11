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

$routes = new RouteCollection();
require BASE_DIR.'/routes/web.php';

return App::$instance = new App(
    container: new DI\Container(require CONFIG_DIR.'/container.php'),
    routes: $routes,
    middlewares: require CONFIG_DIR.'/middleware.php',
);
