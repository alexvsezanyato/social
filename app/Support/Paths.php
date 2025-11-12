<?php

namespace App\Support;

class Paths
{
    public function __construct(
        public private(set) string $base,
        public private(set) string $app,
        public private(set) string $public,
        public private(set) string $config,
        public private(set) string $route,
        public private(set) string $resource,
        public private(set) string $view,
        public private(set) string $storage,
        public private(set) string $cache,
        public private(set) string $log,
        public private(set) string $upload,
    ) {
    }
}
