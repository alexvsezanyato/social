<?php

$fid = $_GET['id'];
if (!preg_match("/[a-zA-Z0-9\-]+\/[a-zA-Z0-9\-]+/", $fid)) exit;
$fid = basename($fid);

$filepath = __DIR__ . '/uploads/' . $fid;
if (!file_exists($filepath)) exit;
$name = $_GET['name'] ?? $fid;
$name = basename($name);
$type = $_GET['type'] ?? 'application/octet-stream';
$size = filesize($filepath);

header('X-Sendfile: ' . realpath($filepath));
header("Content-Type: $type");
header('Content-Disposition: attachment; filename=' . $name);
header('Content-length: ' . $size);

