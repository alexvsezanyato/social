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

$from = $json->from;
$limit = $json->limit;

if (!is_numeric($from)) {
    echo json_encode(['code' => 2]);
    exit;
} 

if (!is_numeric($limit)) $limit = 10;
if ($limit > 10) $limit = 10;
require_once __DIR__ . '/auth/connect.php';
$pdo = connect();

$statement = $pdo->prepare("
    select id, text, authorid, 
    cast(createdat as date) as date, 
    cast(createdat as time) as time 
    from posts where id >= ? 
    order by id desc 
    limit $limit

");

$result = $statement->execute([$from]);

if (!$result) {
    echo json_encode(['code' => 3, 'cause' => $statement->errorInfo()]);
    exit;
}

$data = [];
while ($data[] = $statement->fetch());
array_pop($data);
    
echo json_encode([
    code => 0,
    data => $data
]);
