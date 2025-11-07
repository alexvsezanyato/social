<?php

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

define('BASE_DIR', dirname(__DIR__));
define('RESOURCE_DIR', BASE_DIR.'/resources');
define('VIEW_DIR', RESOURCE_DIR.'/views');
define('CONFIG_DIR', BASE_DIR.'/config');
define('STORAGE_DIR', BASE_DIR.'/storage');
define('CACHE_DIR', STORAGE_DIR.'/cache');
define('BOOTSTRAP_DIR', BASE_DIR.'/bootstrap');
define('VENDOR_DIR', BASE_DIR.'/vendor');

require_once VENDOR_DIR.'/autoload.php';
require_once BOOTSTRAP_DIR.'/app.php';

$uri = parse_url($_SERVER['REQUEST_URI']);
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
