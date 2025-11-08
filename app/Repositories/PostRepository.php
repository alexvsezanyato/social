<?php

namespace App\Services;

class PostRepository {
    public function __construct(
        private Database $database,
    ) {}

    public function exists($id) {
        $statement = $this->database->connection->prepare(
            <<<SQL
            SELECT 1 FROM "post" WHERE "author_id"=:author_id LIMIT 1
            SQL
        );
        $statement->bindParam('author_id', $id);
        $statement->execute();

        if (!$statement) {
            return false;
        }

        return (bool)$statement->fetch();
    }

    public function find(array $filters = []) {
        $statement = $this->database->connection->prepare(
            <<<SQL
            SELECT
                *,
                CAST("created_at" AS date) AS date,
                CAST("created_at" AS time) AS time
            FROM "posts"
            WHERE "authorid"=:author_id
            ORDER BY "id" DESC
            SQL
        );

        foreach ($filters as $k => $v) {
            $statement->bindParam($k, $v);
        }

        $statement->execute();
        return $statement;
    }
}
