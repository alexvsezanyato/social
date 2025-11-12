<?php

namespace App\Helpers;

use App\Helpers\ViewInterface;

class TwigView implements ViewInterface
{
    public function __construct(
        private \Twig\Environment $twig,
    ) {
    }

    function render(string $view, array $params = []): string
    {
        return $this->twig->render("$view.html.twig", $params);
    }
}
