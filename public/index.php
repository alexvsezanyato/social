<?php 

# display all errors
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', E_ALL);

$uri = parse_url(
    $_SERVER['REQUEST_URI'], 
    PHP_URL_PATH
);

$segments = explode('/', trim($uri, '/'));
$controller = ucfirst($segments[0] ?? 'main');
$action = $segments[1] ?? 'index';

# include path should be .
# otherwise you may have include errors
require "./../app/funs.php";
require "./../app/autoload.php";

# require control
# controllers use the root namespace
control($controller, $action);
