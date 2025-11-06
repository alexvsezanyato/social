<?php

function view(string $view, array $params = [])
{
    ob_start();
    $path = BASE_DIR."/resources/views/$view.php";

    if (!file_exists($path)) {
        throw new \Exception("View '$view' does not exist");
    }

    extract($params);
    require $path;
    return ob_get_clean();
}