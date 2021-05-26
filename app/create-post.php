<?php 
require_once __DIR__ . "/../auth/connect.php";
require_once __DIR__ . "/user.php";
// $text = file_get_contents('php://input');

$post = $_POST;
$files = $_FILES;
$text = $post['text'];
$error = false;
$errorcode = 0;

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

$text = htmlspecialchars($text);
$id = User::get()['id'];
$pdo = connect();
$pdo->beginTransaction();

$statement = $pdo->prepare('
    insert into posts (authorid, text) 
    values (?, ?)
');

if (!$statement->execute([$id, $text])) {
    echo '5';
    exit;
} 

$statement = $pdo->prepare('
    select id from posts 
    order by id desc 
    limit 1
');

if (!$statement->execute()) {
    echo '5';
    exit;
}

$result = $statement->fetch();
$pid = $result['id'] ?? '';
$uploadfiles = false;

if (!$pid) {
    echo '5';
    exit;
}

$statement = $pdo->prepare('
    insert into documents (pid, source, mime, name) 
    values (:pid, :source, :mime, :name)
');

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
    $uploadfiles = true;
    $docname = basename($file['name']);

    if (strlen($docname) > 64) { 
        // $error = true;
        // $errorcode = 6;
        echo '6';
        $pdo->rollBack();
        exit;
    }

    $tpath = $file['tmp_name'];
    $newpath = $dir . '/' . $fname;
    move_uploaded_file($tpath, $newpath);

    $result = $statement->execute([
        ':pid' => $pid,
        ':source' => "uploads/$fname",
        ':mime' => $file['type'], 
        ':name' => $docname,
    ]);
}

$pdo->commit();
if ($statement) echo '0';
else echo '1';
