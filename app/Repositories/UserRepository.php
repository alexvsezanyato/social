<?php

namespace App\Services;

class UserRepository {
    public function __construct(
        private Database $database,
    ) {}

    public function find(array $filters = []) {
        $statement = $this->database->connection->prepare(
            <<<SQL
            SELECT * FROM "user" WHERE "id" = :id
            SQL
        );

        foreach ($filters as $k => $v) {
            $statement->bindParam($k, $v);
        }

        $statement->execute();
        return $statement;
    }
}
