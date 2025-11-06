<?php

namespace App\Http\Controllers\Api;

use App\Services\User;
use App\Services\Users;
use PDO;

class IndexController
{
    public function download()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') exit;
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
    }

    public function createPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            echo '6';
            exit;
        }

        $docs = [];
        $pics = [];
        $text = $_POST['text'];

        if (!Users::in()) {
            echo '1';
            exit;
        }

        if (strlen($text) < 5) {
            echo '3';
            exit;
        }

        if (count($_FILES) > 20) {
            echo '4';
            exit;
        }

        if (preg_match('/(\r\n|\r|\n){3,}/', $text)) {
            echo '2';
            exit;
        }

        $picCount = 0;
        $docCount = 0;

        foreach ($_FILES as $k => &$v) {
            if ($k[0] == 'd') $docCount++;
            else if ($k[0] == 'p') $picCount++;
        }

        if ($picCount > 9 || $docCount > 5) {
            echo '5';
            exit;
        }

        foreach ($_FILES as $k => &$v) {
            if ($k[0] == 'd') {
                $docs[] = $v;
            } else if ($k[0] == 'p') {
                $pics[] = $v;
            }
        }

        $text = htmlspecialchars($text);
        $id = Users::get()['id'];
        $pdo = connect();
        $pdo->beginTransaction();

        $statement = $pdo->prepare('insert into "post" ("author_id", "text") values (?, ?)');

        if (!$statement->execute([$id, $text])) {
            $pdo->rollBack();
            echo '5';
            exit;
        } 

        $statement = $pdo->prepare('
            select "id" from "post"
            order by "id" desc 
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
            insert into "document" ("pid", "source", "mime", "name") 
            values (:pid, :source, :mime, :name)
        ');

        $i = 0;

        foreach ($docs as $file) {
            if ($file['error']) {
                continue;
            }

            $dir = BASE_DIR.'/public/uploads/documents';
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

            if (!move_uploaded_file(from: $file['tmp_name'], to: "$dir/$fname")) {
                $pdo->rollBack();
                echo '9';
                exit;
            }

            $result = $statement->execute([
                ':pid' => $pid,
                ':source' => "docs/$fname",
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

        $statement = $pdo->prepare('
            insert into "picture" ("pid", "source", "mime", "name") 
            values (:pid, :source, :mime, :name)
        ');

        $i = 0;

        foreach ($pics as $file) {
            if ($file['error']) {
                continue;
            }

            $dir = BASE_DIR.'/public/uploads/pictures';
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

            $status = move_uploaded_file(from: $file['tmp_name'], to: "$dir/$fname");
            
            if (!$status) {
                $pdo->rollBack();
                echo '9';
                exit;
            }

            $result = $statement->execute([
                ':pid' => $pid,
                ':source' => $fname,
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
    }

    public function applyProfile()
    {
        if (!User::in()) {
            echo '1';
            exit;
        }

        $public = $_POST['public'];

        if (!preg_match('/^[0-9a-zA-Z\ ]{3,20}$/', $public)) {
            echo '2';
            exit;
        }

        $user = User::get();
        $id = $user['id'];
        $pdo = connect();
        $statement = $pdo->prepare('update "user" set "public"=? where "id"=?');
        $result = $statement->execute([$public, $id]);

        if (!$result) {
            echo '1';
            exit;
        }
        else {
            echo '0';
            exit;
        }
    }

    public function removePost()
    {
        $content = file_get_contents('php://input');

        if (!preg_match('/^[0-9]+$/', $content)) {
            echo '1';
            exit; 
        }

        if (!Users::in()) { 
            echo '2';
            exit;
        }

        $pdo = connect();
        $statement = $pdo->prepare('select 1 from "post" where "id"=? and "author_id"=? limit 1');
        $result = $statement->execute([$content, Users::id()]);

        if (!$result) { 
            echo '2';
            exit;
        }

        if (!isset($statement->fetch()['1'])) {
            echo '2';
            exit;
        }

        $statement = $pdo->prepare('delete from "post" where "id"=?');
        $result = $statement->execute([$content]);

        if (!$result) { 
            echo '2';
            exit;
        }

        $statment = $pdo->prepare('delete from "document" where "pid"=?');
        $result = $statment->execute([$content]);

        if (!$result) {
            echo '2';
            exit;
        }

        echo '0';
        exit;
    }

    public function posts()
    {
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
        $pdo = connect();

        $params = [];

        $sql = '
            select "id", "text", "author_id", 
            cast("created_at" as date) as date, 
            cast("created_at" as time) as time 
            from "post"
        ';

        if ($from > 0) {
            $sql = $sql . 'where "id" <= :id' . ' ';
            $params[':id'] = $from;
        }

        $sql = $sql . "order by \"id\" desc limit $limit";
        $postsql = $pdo->prepare($sql);

        $docsql = $pdo->prepare('
            select "name", "source", "mime"
            from "document"
            where "pid" = ?
            limit 20
        ');

        $picsql = $pdo->prepare('
            select "name", "source", "mime"
            from "picture"
            where "pid" = ?
            limit 20
        ');

        $postr = $postsql->execute($params);
        $posts = [];
        $user = Users::get();    

        if (!$postr) {
            echo json_encode([
                'code' => 3, 
                'cause' => $postsql->errorinfo(),
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
    }
}
