<?php
$json = json_decode(file_get_contents('php://input'));

if (!$json) {
    echo json_encode(['code' => 1]);
    exit;
}

if (!isset($json->from) || !isset($json->limit)) {
    echo json_encode(['code' => 2]);
    exit;
}

$from = $json->from ?? 0;
$limit = $json->limit ?? 1;

if (!is_numeric($from)) {
    echo json_encode(['code' => 2]);
    exit;
} 

if (!is_numeric($limit)) $limit = 10;
if ($limit > 10) $limit = 10;
require_once __DIR__ . '/auth/connect.php';
require_once __DIR__ . '/app/users.php';
$pdo = connect();

$sql = "
    select id, text, authorid, 
    cast(createdat as date) as date, 
    cast(createdat as time) as time 
    from posts  
";

if ($from > 0) $sql = $sql . "where id <= :id" . " ";
$sql = $sql . "order by id desc limit $limit";
$postsql = $pdo->prepare($sql);

$docsql = $pdo->prepare('
    select name, source, mime
    from documents 
    where pid = ? 
    limit 20
');

$picsql = $pdo->prepare('
    select name, source, mime
    from pictures 
    where pid = ? 
    limit 20
');

// result of executing posts
$postr = $postsql->execute([":id" => $from]);
$posts = [];
$user = Users::get();    

if (!$postr) {
    echo json_encode([
        'code' => 3, 
        'cause' => $postsql->errorInfo(),
    ]);
    exit;
}

while ($post = $postsql->fetch()) {
    $docr = $docsql->execute([$post['id']]);
    // if result(docs) fails
    if (!$docr) continue;

    $docs = [];
    while ($doc = $docsql->fetch()) $docs[] = $doc;

    $picr = $picsql->execute([$post['id']]);
    if (!$picr) continue;

    $pics = [];
    while ($pic = $picsql->fetch()) $pics[] = $pic;

    $post['docs'] = $docs;
    $post['pics'] = $pics;
    $posts[] = $post;
}

echo json_encode([
    'code' => 0,
    'posts' => $posts,

    'user' => [
        'id' => $user['id'],
        'public' => $user['public'],
    ]
]);
