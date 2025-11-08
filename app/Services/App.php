<?php

namespace App\Services;

class App {
    public static ?self $instance;

    public function __construct(
        public private(set) \Di\Container $container,
    ) {}
}
