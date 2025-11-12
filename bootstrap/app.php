<?php

use DI\Container;
use App\Support\Paths;
use App\Services\App;

$paths = new Paths(
    base:     BASE_DIR,
    app:      BASE_DIR.'/app',
    public:   BASE_DIR.'/public',
    config:   BASE_DIR.'/config',
    route:    BASE_DIR.'/routes',
    resource: BASE_DIR.'/resources',
    view:     BASE_DIR.'/resources/views',
    storage:  BASE_DIR.'/storage',
    cache:    BASE_DIR.'/storage/cache',
    log:      BASE_DIR.'/storage/logs',
    upload:   BASE_DIR.'/storage/uploads',
);

$container = new Container(require BASE_DIR.'/config/container.php');
$container->set(Paths::class, $paths);
$config = $container->get('config');

$app = new App(
    container: $container,
    middlewares: $config['middleware'],
);

$container->set(App::class, $app);
return $app;