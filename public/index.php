<?php

use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use App\Services\App;

define('BASE_DIR', dirname(__DIR__));
require_once BASE_DIR.'/vendor/autoload.php';
Dotenv::createImmutable(BASE_DIR)->safeLoad();

/**
 * @var App
 */
$app = require BASE_DIR.'/bootstrap/app.php';

$response = $app->handleRequest(Request::createFromGlobals());
$response->send();