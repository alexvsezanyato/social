<?php 
require_once __DIR__ . "/../auth/connect.php";

class Documents {
    function user($user) {
        $this->user = $user;
        return $this;
    }

    function pid($postid) {
        $this->postid = $postid;
        return $this;
    }

    function get() {
        if (!$this->db) $this->db = connect();
        $pdo = $this->db;

        $statement = $pdo->prepare('
            select * from documents 
            where id >= :id
            and pid = :pid
            limit 10
        ');

        $statement->execute([
            ':id' => $this->from,
            ':pid' => $this->postid,
        ]);

        return $statement;
    }

    private $user = [];
    private $postid = [];
    private $from = 0;
    private $limit = 10;
    private $db;
}
