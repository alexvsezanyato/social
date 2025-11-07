<?php

namespace App\Dto;

class Location {
    public function __construct(
        public private(set) $base,
        public private(set) $rosource,
        public private(set) $view,
        public private(set) $config,
        public private(set) $storage,
        public private(set) $cache,
        public private(set) $bootstrap,
        public private(set) $vendor,
    ) {}
}
