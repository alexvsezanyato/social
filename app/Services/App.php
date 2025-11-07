<?php

namespace App\Services;

class App {
    public static ?self $instance;

    # public static ?self $instance {
    #     set (self $instance) {
    #         if (self::$instance) {
    #             throw new \Exception('The application instance is already set');
    #         }

    #         $this->instance = $instance;
    #     }

    #     get () {
    #         if (self::$instance === null) {
    #             throw new \Exception('The application instance is not set');
    #         }

    #         return self::$instance;
    #     }
    # }

    public function __construct(
        public private(set) \Di\Container $container,
    ) {}
}
