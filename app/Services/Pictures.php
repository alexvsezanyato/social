<?php 

namespace App\Services;

use PDOStatement;

class Pictures {
    private array $user = [];
    private int $postId = -1;
    private int $from = 0;
    private int $limit = 10;

    public function __construct(
        private Database $database,
    ) {}

    public function user(array $user): self {
        $this->user = $user;
        return $this;
    }

    public function pid(int $postId): self {
        $this->postId = $postId;
        return $this;
    }

    public function get(): ?PDOStatement {
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

        $statement->execute([
            ':id' => $this->from,
            ':pid' => $this->postId,
        ]);

        return $statement;
    }
}
