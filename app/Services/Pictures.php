<?php 

namespace App\Services;

class Pictures {
    function user(array $user): self {
        $this->user = $user;
        return $this;
    }

    function pid(int $postid): self {
        $this->postid = $postid;
        return $this;
    }

    function get(): ?PDOStatement {
        if (!$this->db) $this->db = connect();
        $pdo = $this->db;

        $statement = $pdo->prepare('
            select * from pictures
            where id >= :id
            and pid = :pid
            limit 10
        ');

        if (!$statement) { 
            // sql request is failed
            // handle that
            return null;
        }

        $statement->execute([
            ':id' => $this->from,
            ':pid' => $this->postid,
        ]);

        return $statement;
    }

    private array $user = [];
    private array $postid = [];
    private int $from = 0;
    private int $limit = 10;
    private ?PDO $db;
}
