<?php

namespace App\Services;

class Vite
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