<?php

use DI\Container;
use Dotenv\Dotenv;
use App\Support\Paths;
use App\Services\App;

$dotenv = Dotenv::createImmutable(BASE_DIR);
$dotenv->safeLoad();

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

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(BASE_DIR.'/helpers/'),
);

foreach ($iterator as $file) {
    if ($file->getExtension() === 'php') {
        require_once $file->getPathname();
    }
}

$container = new Container(require BASE_DIR.'/config/container.php');
$container->set(Paths::class, $paths);
$config = $container->get('config');

return App::$instance = new App(
    container: $container,
    middlewares: $config['middleware'],
);
