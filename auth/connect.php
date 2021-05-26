<?php 

function connect() {
    static $connected;
    static $pdo;
    if ($connected === true) return $pdo;
    $pdo = new PDO('mysql:host=localhost;dbname=phpsession;charset=utf8', 'alexsql', 'regular');
    if ($pdo) $connected = true;
    return $pdo;
}
