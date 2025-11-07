<?php 

namespace App\Services;

use PDO;
use PDOStatement;
use App\Services\Database;

class Documents {
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

        $pdo = $this->database->connection;

        $statement = $pdo->prepare(
            <<<SQL
            SELECT *
            FROM "documents" 
            WHERE "id" >= :id
            AND "pid" = :pid
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
