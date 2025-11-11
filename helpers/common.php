<?php

function env(string $key, mixed $default = null): mixed {
    return $_ENV[$key] ?? $default;
}