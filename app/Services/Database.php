<?php

namespace App\Services;

use PDO;

class Database
{
    public function __construct(
        public private(set) PDO $connection,
    ) {}
}