<?php

use Symfony\Component\HttpFoundation\Request;

use App\Services\App;

define('BASE_DIR', dirname(__DIR__));
require BASE_DIR.'/constants.php';

if (DEBUG_MODE) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

require_once VENDOR_DIR.'/autoload.php';

/**
 * @var App
 */
$app = require BOOTSTRAP_DIR.'/app.php';

$response = $app->handleRequest($app->container->make(Request::class));
$response->send();
