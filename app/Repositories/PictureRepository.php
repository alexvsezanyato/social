<?php 

namespace App\Services;

use PDOStatement;

class PictureRepository {
    public function __construct(
        private Database $database,
    ) {}

    public function find(array $filters = []): ?PDOStatement {
        $statement = $this->database->connection->prepare(
            <<<SQL
            SELECT *
            FROM "pictures"
            WHERE "id" >= :id AND "pid" = :pid
            LIMIT 10
            SQL
        );

        if (!$statement) { 
            return null;
        }

        foreach ($filters as $k => $v) {
            $statement->bindParam($k, $v);
        }

        $statement->execute();
        return $statement;
    }
}
