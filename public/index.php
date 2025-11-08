<?php

use Symfony\Component\HttpFoundation\Request;
use App\Services\App;

define('BASE_DIR', dirname(__DIR__));
define('RESOURCE_DIR', BASE_DIR.'/resources');
define('VIEW_DIR', RESOURCE_DIR.'/views');
define('CONFIG_DIR', BASE_DIR.'/config');
define('STORAGE_DIR', BASE_DIR.'/storage');
define('CACHE_DIR', STORAGE_DIR.'/cache');
define('BOOTSTRAP_DIR', BASE_DIR.'/bootstrap');
define('VENDOR_DIR', BASE_DIR.'/vendor');

require_once VENDOR_DIR.'/autoload.php';

/**
 * @var App
 */
$app = require BOOTSTRAP_DIR.'/app.php';

$app->handleRequest(Request::createFromGlobals());
