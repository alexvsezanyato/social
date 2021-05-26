<?php 
session_start();
echo 'cid: ' . $_SESSION['cid'];
// $cvcount = $_COOKIE['vcount'] ?? 0;
// setcookie('vcount', $cvcount, strtotime('+30 days'));

function get($i) {
    $s = $_SESSION[$i] ?? null;
    return $s;
}

function set($i, $data) {
    $_SESSION[$i] = $data;
    return;
}

if (!array_key_exists('cid', $_SESSION)) set('cid', 1);
$pdo = new PDO('mysql:host=localhost;dbname=phpsession', 'alexsql', 'regular');

if ($pdo) {
    // .
    echo 'Success';
} else {
    echo 'Fail';
    die();
}

$statement = $pdo->query('select value from tdb where id=' . get('cid'));
if ($statement) $row = $statement->fetch();
else $row = [];
$pdo->query('update tdb set value=' . ++$row['value'] . ' where id=' . get('cid'));
echo ++$row['value'] ?? '';
phpinfo();
session_write_close();
