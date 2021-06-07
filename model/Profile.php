<?php 
require_once __DIR__ . '/user.php';

if (!User::in()) {
    echo '1';
    exit;
}

$post = $_POST;
$public = $post['public'];

if (!preg_match('/^[0-9a-zA-Z\ ]{3,20}$/', $public)) {
    echo '2';
    exit;
}

$user = User::get();
$id = $user['id'];
require_once __DIR__ . '/../auth/connect.php';
$pdo = connect();
$statement = $pdo->prepare("update users set public=? where id=?");
$result = $statement->execute([$public, $id]);

if (!$result) {
    echo '1';
    exit;
}
else {
    echo '0';
    exit;
}

