<?php 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '6';
    exit;
}

require_once __DIR__ . "/../auth/connect.php";
require_once __DIR__ . "/users.php";

$post = $_POST;
$files = $_FILES;
$text = $post['text'];
$error = false;
$errorcode = 0;

if (!Users::in()) {
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
$id = Users::get()['id'];
$pdo = connect();
$pdo->beginTransaction();

$statement = $pdo->prepare('
    insert into posts (authorid, text) 
    values (?, ?)
');

if (!$statement->execute([$id, $text])) {
    $pdo->rollBack();
    echo '5';
    exit;
} 

$statement = $pdo->prepare('
    select id from posts 
    order by id desc 
    limit 1
');

if (!$statement->execute()) {
    $pdo->rollBack();
    echo '5';
    exit;
}

$result = $statement->fetch();
$pid = $result['id'] ?? '';
$uploadfiles = false;

if (!$pid) {
    $pdo->rollBack();
    echo '5';
    exit;
}

$statement = $pdo->prepare('
    insert into documents (pid, source, mime, name) 
    values (:pid, :source, :mime, :name)
');

$i = 0;

foreach ($files as $file) {
    if ($file['error']) continue;
    $user = Users::get();
    $uid = Users::id();
    $dir = __DIR__ . "/../uploads";
    $fname = "";
    $helper = false;
    $fname = $pid . $i; // uniqid($pid . $i, true);

    if (file_exists($dir . '/' . $fname)) {
        $pdo->rollBack();
        echo '7';
        exit;
    }

    $uploadfiles = true;
    $docname = basename($file['name']);

    if (strlen($docname) > 64) { 
        echo '6';
        $pdo->rollBack();
        exit;
    }

    $tpath = $file['tmp_name'];
    $newpath = $dir . '/' . $fname;
    $status = move_uploaded_file($tpath, $newpath);
    
    if (!$status) {
        $pdo->rollBack();
        echo '9';
        exit;
    }

    $result = $statement->execute([
        ':pid' => $pid,
        ':source' => "uploads/$fname",
        ':mime' => $file['type'], 
        ':name' => $docname,
    ]);

    if (!$result) {
        $pdo->rollBack();
        echo '9';
        exit;
    }

    $i++;
}

$pdo->commit();
echo '0';
