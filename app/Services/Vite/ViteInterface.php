<?php

namespace App\Services\Vite;

interface ViteInterface
{
    public function asset(string $source): string;
}