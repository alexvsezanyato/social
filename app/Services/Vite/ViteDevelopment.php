<?php

namespace App\Services\Vite;

class ViteDevelopment implements ViteInterface
{
    public function __construct(
        private string $url,
    ) {
    }

    public function asset(string $source): string
    {
        return $this->url.'/'.$source;
    }
}