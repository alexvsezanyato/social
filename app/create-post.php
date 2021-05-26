<?php 
require_once __DIR__ . "/../auth/connect.php";
require_once __DIR__ . "/user.php";
// $text = file_get_contents('php://input');
$post = $_POST;
$files = $_FILES;
$text = $post['text'];

if (!User::in()) {
    echo '1';
    exit;
}

if (strlen($text) < 5) {
    echo '3';
    exit;
}

if (count($files) > 5) {
    echo '4';
    exit;
}

if (preg_match('/(\r\n|\r|\n){3,}/', $text)) {
    echo '2';
    exit;
}

foreach ($files as $file) {
    if ($file['error']) continue;
    $user = User::get();
    $uid = User::id();
    $dir = __DIR__ . "/../uploads";
    $fname = "";
    $helper = false;
    $i = 5;

    while (--$i) {
        $fname = $uid . '-' . uniqid();
        if (!file_exists($dir . '/' . $fname)) break;
    }

    if (!$i) continue;
    $docname = basename($file['name']);
    $tpath = $file['tmp_name'];
    $newpath = $dir . '/' . $fname;
    move_uploaded_file($tpath, $newpath);
}

$text = htmlspecialchars($text);
$id = User::get()['id'];
$pdo = connect();
$statement = $pdo->prepare('insert into posts (authorid, text) values (?, ?)');
$statement->execute([$id, $text]);

if ($statement) echo '0';
else echo '1';
