<?php

namespace App\Http\Controllers\Api;

use App\Services\Database;
use App\Services\User;
use Symfony\Component\HttpFoundation\Response;

class PostController
{
    public function download()
    {
        $fid = $_GET['id'];

        if (!preg_match("/[a-zA-Z0-9\-]+\/[a-zA-Z0-9\-]+/", $fid)) {
            return new Response(1);
        }

        $fid = basename($fid);

        $filepath = __DIR__ . '/uploads/' . $fid;

        if (!file_exists($filepath)) {
            return new Response(2);
        }

        $name = $_GET['name'] ?? $fid;
        $name = basename($name);
        $type = $_GET['type'] ?? 'application/octet-stream';
        $size = filesize($filepath);

        header('X-Sendfile: ' . realpath($filepath));
        header("Content-Type: $type");
        header('Content-Disposition: attachment; filename=' . $name);
        header('Content-length: ' . $size);

        return new Response(0);
    }

    public function create(User $user, Database $database)
    {
        $docs = [];
        $pics = [];
        $text = $_POST['text'];

        if (!$user->in()) {
            return new Response(1);
        }

        if (strlen($text) < 5) {
            return new Response(3);
        }

        if (count($_FILES) > 20) {
            return new Response(4);
        }

        if (preg_match('/(\r\n|\r|\n){3,}/', $text)) {
            return new Response(2);
        }

        $picCount = 0;
        $docCount = 0;

        foreach ($_FILES as $k => &$v) {
            if ($k[0] == 'd') {
                $docCount++;
            } else if ($k[0] == 'p') {
                $picCount++;
            }
        }

        if ($picCount > 9 || $docCount > 5) {
            return new Response(5);
        }

        foreach ($_FILES as $k => &$v) {
            if ($k[0] == 'd') {
                $docs[] = $v;
            } else if ($k[0] == 'p') {
                $pics[] = $v;
            }
        }

        $text = htmlspecialchars($text);
        $id = $user->get()['id'];
        $database->connection->beginTransaction();

        $statement = $database->connection->prepare(
            <<<SQL
            INSERT INTO "post"("author_id", "text") VALUES (?, ?)
            SQL
        );

        if (!$statement->execute([$id, $text])) {
            $database->connection->rollBack();
            return new Response(5);
        } 

        $statement = $database->connection->prepare(
            <<<SQL
            SELECT "id" FROM "post" ORDER BY "id" DESC LIMIT 1
            SQL
        );

        if (!$statement->execute()) {
            $database->connection->rollBack();
            return new Response(5);
        }

        $result = $statement->fetch();
        $pid = $result['id'] ?? '';
        $uploadfiles = false;

        if (!$pid) {
            $database->connection->rollBack();
            return new Response(5);
        }

        $statement = $database->connection->prepare(
            <<<SQL
            INSERT INTO "document"("pid", "source", "mime", "name") 
            VALUES (:pid, :source, :mime, :name)
            SQL
        );

        $i = 0;

        foreach ($docs as $file) {
            if ($file['error']) {
                continue;
            }

            $dir = BASE_DIR.'/public/uploads/documents';
            $fname = $pid . $i; // uniqid($pid . $i, true);

            if (file_exists($dir . '/' . $fname)) {
                $database->connection->rollBack();
                return new Response(7);
            }

            $uploadfiles = true;
            $docname = basename($file['name']);

            if (strlen($docname) > 64) { 
                $database->connection->rollBack();
                return new Response(6);
            }

            if (!move_uploaded_file(from: $file['tmp_name'], to: "$dir/$fname")) {
                $database->connection->rollBack();
                return new Response(9);
            }

            $result = $statement->execute([
                ':pid' => $pid,
                ':source' => "docs/$fname",
                ':mime' => $file['type'], 
                ':name' => $docname,
            ]);

            if (!$result) {
                $database->connection->rollBack();
                return new Response(9);
            }

            $i++;
        }

        $statement = $database->connection->prepare(
            <<<SQL
            INSERT INTO "picture"("pid", "source", "mime", "name")
            VALUES (:pid, :source, :mime, :name)
            SQL
        );

        $i = 0;

        foreach ($pics as $file) {
            if ($file['error']) {
                continue;
            }

            $dir = BASE_DIR.'/public/uploads/pictures';
            $fname = $pid . $i; // uniqid($pid . $i, true);

            if (file_exists($dir . '/' . $fname)) {
                $database->connection->rollBack();
                return new Response(7);
            }

            $uploadfiles = true;
            $docname = basename($file['name']);

            if (strlen($docname) > 64) { 
                $database->connection->rollBack();
                return new Response(6);
            }

            $status = move_uploaded_file(from: $file['tmp_name'], to: "$dir/$fname");
            
            if (!$status) {
                $database->connection->rollBack();
                return new Response(9);
            }

            $result = $statement->execute([
                ':pid' => $pid,
                ':source' => $fname,
                ':mime' => $file['type'], 
                ':name' => $docname,
            ]);

            if (!$result) {
                $database->connection->rollBack();
                return new Response(9);
            }

            $i++;
        }

        $database->connection->commit();
        return new Response(0);
    }

    public function remove(User $user, Database $database)
    {
        $content = file_get_contents('php://input');

        if (!preg_match('/^[0-9]+$/', $content)) {
            return new Response(1);
        }

        if (!$user->in()) { 
            return new Response(2);
        }

        $statement = $database->connection->prepare(
            <<<SQL
            SELECT 1 AS "result" FROM "post" WHERE "id"=? AND "author_id"=? LIMIT 1
            SQL
        );

        $result = $statement->execute([$content, $user->id()]);

        if (!$result) { 
            return new Response(2);
        }

        if (!isset($statement->fetch()['result'])) {
            return new Response(2);
        }

        $statement = $database->connection->prepare(
            <<<SQL
            DELETE FROM "post" WHERE "id"=?
            SQL
        );

        $result = $statement->execute([$content]);

        if (!$result) { 
            return new Response(2);
        }

        $statment = $database->connection->prepare(
            <<<SQL
            DELETE FROM "document" WHERE "pid"=?
            SQL
        );

        $result = $statment->execute([$content]);

        if (!$result) {
            return new Response(2);
        }

        return new Response(0);
    }

    public function posts(User $user, Database $database)
    {
        $json = json_decode(file_get_contents('php://input'));

        if (!$json) {
            return new Response(json_encode(['code' => 1]));
        }

        if (!isset($json->from) || !isset($json->limit)) {
            return new Response(json_encode(['code' => 2]));
        }

        $from = $json->from ?? 0;
        $limit = $json->limit ?? 1;

        if (!is_numeric($from)) {
            return new Response(json_encode(['code' => 2]));
        } 

        if (!is_numeric($limit)) $limit = 10;
        if ($limit > 10) $limit = 10;

        $params = [];

        $sql = <<<SQL
            SELECT
                "id",
                "text",
                "author_id", 
                CAST("created_at" AS date) AS date, 
                CAST("created_at" AS time) AS time 
            FROM "post"
            SQL;

        if ($from > 0) {
            $sql = $sql . 'WHERE "id" <= :id' . ' ';
            $params[':id'] = $from;
        }

        $sql = $sql . "ORDER BY \"id\" DESC LIMIT $limit";
        $postsql = $database->connection->prepare($sql);

        $docsql = $database->connection->prepare(
            <<<SQL
            SELECT "name", "source", "mime"
            FROM "document"
            WHERE "pid" = ?
            LIMIT 20
            SQL
        );

        $picsql = $database->connection->prepare(
            <<<SQL
            SELECT "name", "source", "mime"
            FROM "picture"
            WHERE "pid" = ?
            LIMIT 20
            SQL
        );

        $postr = $postsql->execute($params);
        $posts = [];
        $user = $user->get();    

        if (!$postr) {
            return new Response(json_encode([
                'code' => 3, 
                'cause' => $postsql->errorinfo(),
            ]));
        }

        while ($post = $postsql->fetch()) {
            $docr = $docsql->execute([$post['id']]);

            if (!$docr) {
                continue;
            }

            $docs = [];

            while ($doc = $docsql->fetch()) {
                $docs[] = $doc;
            }

            $picr = $picsql->execute([$post['id']]);

            if (!$picr) {
                continue;
            }

            $pics = [];

            while ($pic = $picsql->fetch()) {
                $pics[] = $pic;
            }

            $post['docs'] = $docs;
            $post['pics'] = $pics;
            $posts[] = $post;
        }

        return new Response(json_encode([
            'code' => 0,
            'posts' => $posts,

            'user' => [
                'id' => $user['id'],
                'public' => $user['public'],
            ]
        ]));
    }
}
