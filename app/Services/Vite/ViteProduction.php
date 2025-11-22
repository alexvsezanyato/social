<?php

namespace App\Services\Vite;

class ViteProduction implements ViteInterface
{
    public function __construct(
        private array $manifest = [],
    ) {
    }

    public function asset(string $source): string
    {
        return '/'.$this->manifest[$source]['file'];
    }
}