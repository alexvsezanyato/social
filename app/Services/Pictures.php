<?php 

namespace App\Services;

use PDO;
use PDOStatement;

class Pictures {
    private array $user = [];
    private int $postId = -1;
    private int $from = 0;
    private int $limit = 10;
    private ?PDO $db;

    public function user(array $user): self {
        $this->user = $user;
        return $this;
    }

    public function pid(int $postId): self {
        $this->postId = $postId;
        return $this;
    }

    public function get(): ?PDOStatement {
        if (!$this->db) $this->db = connect();
        $pdo = $this->db;

        $statement = $pdo->prepare('
            select * from pictures
            where id >= :id
            and pid = :pid
            limit 10
        ');

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
