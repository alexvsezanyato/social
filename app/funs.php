<?php 

function section(string $s): string {
    $path = root() . "/views/sections/$s.php";
    return $path;
}

function root(): string {
    $root = realpath(__DIR__ . '/..');
    return $root;
}

function view(string $view, array $vars = []) {
    extract($vars);
    unset($vars);

    require root() . "/views/$view.php";
    return;
} 

function control(string $controller, string $action) {
    require root() . "/control/$controller.php";
    $instance = new $controller();
    $instance->$action();
    return;
}
