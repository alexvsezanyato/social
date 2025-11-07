<?php

function connect(): PDO {
    $config = require CONFIG_DIR.'/db.php';
    ['host' => $host, 'dbname' => $dbname, 'user' => $user, 'password' => $password] = $config['connection'];

    return new PDO(
        dsn: "pgsql:host=$host;dbname=$dbname", 
        username: $user, 
        password: $password,
    );
}
