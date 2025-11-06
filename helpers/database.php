<?php
function connect(): ?PDO {
    static $pdo;
    if (!empty($pdo)) return $pdo;

    $config = require BASE_DIR.'/config.php';
    $db = $config['db']['connection'];

    try {
        return new PDO(
            dsn: "pgsql:host=$db[host];dbname=$db[dbname]", 
            username: $db['user'], 
            password: $db['password'],
        );
    } catch (PDOException $e) {
        http_response_code(404);
        echo $e->getMessage();
        exit;
    }
}
