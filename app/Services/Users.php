<?php

namespace App\Services;

use PDOStatement;

class Users {
    private ?PDOStatement $user = null;
    private ?bool $in = null;
    private ?int $id = null;

    public function __construct(
        private Database $database,
    ) {}

    public function get(): iterable {
        if ($this->user !== null) {
            return $this->user;
        }

        $statement = $this->database->connection->prepare('SELECT * FROM "user" WHERE "id"=?');
        $result = $statement->execute([$this->id()]);

        if ($result) {
            $statement = $statement->fetch();
        }

        return $statement ?: [];
    }

    public function id(): ?int {
        if ($this->id !== null) {
            return $this->id;
        }

        if (!$this->in()) {
            return null;
        }

        if ($this->id === null) {
            return null;
        } else {
            return $this->id;
        }
    }

    public function in(): bool {
        if ($this->in !== null) {
            return $this->in;
        }

        session_start();
        $id = $_SESSION['id'] ?? null;
        $hash = $_SESSION['hash'] ?? null;
        session_commit();

        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $hashHit = $hash === hash('md5', $userAgent ?? 'agent');

        if ($id !== null && $hashHit) { 
            $this->id = $id;
            return $this->in = true; 
        }

        if (!isset($_COOKIE['pid'])) {
            return $this->in = false;
        }

        $cookie = $_COOKIE['pid'];
        $data = explode('-', $cookie);
        $id = $data[0] ?? null;
        $this->id = $id;
        $hash = $data[1];
        $random = $this->get()['random'];
        $hashHit = ($hash === hash('sha256', $id . $random));

        if ($id !== null && $hashHit) {
            return $this->in = true;
        }

        $this->id = null;
        return $this->in = false;
    }
}
