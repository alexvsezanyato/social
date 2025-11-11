<?php

use Symfony\Component\HttpFoundation\Request;
use App\Services\App;

define('BASE_DIR', dirname(__DIR__));
require_once BASE_DIR.'/vendor/autoload.php';

/**
 * @var App
 */
$app = require BASE_DIR.'/bootstrap/app.php';

$response = $app->handleRequest($app->container->make(Request::class));
$response->send();
