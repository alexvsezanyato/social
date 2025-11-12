<?php

namespace App\Helpers;

use DI\Container;

class View
{
    public function __construct(
        private Container $container,
    ) {}

    function render(string $view, array $params = [])
    {
        $twig = $this->container->make(\Twig\Environment::class);
        return $twig->render("$view.html.twig", $params);
    }
}
