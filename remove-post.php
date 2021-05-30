<?php
$content = file_get_contents('php://input');

if (!preg_match('/^[0-9]+$/', $content)) {
    echo '1';
    exit; 
}

require_once __DIR__ . '/app/users.php';

if (!Users::in()) { 
    echo '2';
    exit;
}

require_once __DIR__ . '/auth/connect.php';
$pdo = connect();
$statement = $pdo->prepare('select 1 from posts where id=? and authorid=? limit 1');
$result = $statement->execute([$content, Users::id()]);

if (!$result) { 
    echo '2';
    exit;
}

if (!isset($statement->fetch()['1'])) {
    echo '2';
    exit;
}

$statement = $pdo->prepare('delete from posts where id=?');
$result = $statement->execute([$content]);

if (!$result) { 
    echo '2';
    exit;
}

$statment = $pdo->prepare('delete from documents where pid=?');
$result = $statment->execute([$content]);

if (!$result) {
    echo '2';
    exit;
}

echo '0';
exit;
