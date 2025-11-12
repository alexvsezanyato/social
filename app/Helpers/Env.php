<?php

namespace App\Helpers;

class Env
{
    static function get(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}
