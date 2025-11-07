<?php

use App\Services\App;

function view(string $view, array $params = [])
{
    $twig = App::$instance->container->make(\Twig\Environment::class);
    return $twig->render("$view.html.twig", $params);
}
