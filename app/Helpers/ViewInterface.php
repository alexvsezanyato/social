<?php

namespace App\Helpers;

interface ViewInterface
{
    public function render(string $view, array $params = []): string;
}
