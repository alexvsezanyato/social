<?php 

function connect(): ?PDO {
    static $connected;
    static $pdo;
    if ($connected === true) return $pdo;

    static $db;
    $db = require_once root() . '/config/dbdata.php';

    try {
        $pdo = new PDO(
            // host
            "mysql:host=${db['host']};" . 

            // database name
            "dbname=${db['db']};" . 

            // charset 
            "charset=${db['charset']}", 

            // database username
            $db['user'], 

            // database password
            $db['key'],
        );

        $connected = true;
        return $pdo;
    } 
    
    catch (PDOException $e) {
        $pdo = false;
        $connected = false;
        return null;
    }

    finally {
        // do anyway
        // ..
    }
}
