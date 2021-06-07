<?php

spl_autoload_register(function($class) {
    $root = $_SERVER['DOCUMENT_ROOT'];
    $separator = DIRECTORY_SEPARATOR;
    $path = str_replace('\\', $separator, $class);
    require root() . "/$path.php";
    return;
});
