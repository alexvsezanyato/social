<?php

namespace App\Services;

class Posts {
    public $posts = null;

    public function exists($id) {
        $pdo = connect();
        $statement = $pdo->prepare('SELECT 1 FROM "posts" WHERE "authorid"=? LIMIT 1');
        $statement->execute([$id]);

        if (!$statement) {
            return false;
        }

        if ($statement->fetch()) {
            return true;
        } else {
            return false;
        }
    }

    public function fetch($id) {
        $pdo = connect();
        
        if ($this->posts === null) {
            $statement = $pdo->prepare(
                <<<SQL
                SELECT
                    *, 
                    CAST(createdat AS date) AS date, 
                    CAST(createdat AS time) AS time 
                FROM posts 
                WHERE "authorid"=? 
                ORDER BY "id" DESC 
                SQL
            );

            $statement->execute([$id]);

            if (!$statement) {
                return null;
            }

            if ($row = $statement->fetch()) {
                $this->posts = $statement;
                return $row;
            }
        } else {
            $row = $this->posts->fetch();

            if (!$row) {
                $this->posts = null;
                return null;
            }

            return $row;
        }
    }
}
