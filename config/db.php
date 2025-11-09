<?php

return [
    'connection' => [
        'dbname' => 'social',
        'user' => 'postgres',
        'password' => '',
        'host' => 'localhost',
        'driver' => 'pdo_pgsql',
        'driverOptions' => [
            PDO::ATTR_CASE => PDO::CASE_NATURAL,
        ],
    ],
];
