<?php

namespace App\Services;

class User {
    public function __construct(
        private Database $database,
    ) {}

    public function get(): iterable {
        $statement = $this->database->connection->prepare(
            <<<SQL
            SELECT * FROM "user" WHERE "id"=:id
            SQL
        );
        $id = $this->id();
        $statement->bindParam('id', $id);
        $result = $statement->execute();

        if (!$result) {
            return [];
        }

        return $statement->fetch();
    }

    public function in(): bool {
        session_start();
        $id = $_SESSION['id'] ?? null;
        $hash = $_SESSION['hash'] ?? null;
        session_commit();

        $hashHit = $hash === hash('md5', $_SERVER['HTTP_USER_AGENT'] ?? 'agent');

        if ($id !== null && $hashHit) { 
            return true; 
        }

        if (!isset($_COOKIE['pid'])) {
            return false;
        }

        $cookie = $_COOKIE['pid'];
        $data = explode('-', $cookie);
        $id = $data[0] ?? null;
        $hash = $data[1];
        $random = $this->get()['random'];
        $hashHit = ($hash === hash('sha256', $id . $random));

        if ($id !== null && $hashHit) {
            return true;
        }

        return false;
    }

    public function id(): ?int {
        session_start();
        $id = $_SESSION['id'] ?? null;
        session_commit();

        if ($id !== null) {
            return $id;
        }

        if (!isset($_COOKIE['pid'])) {
            return null;
        }

        ['pid' => $pid] = $_COOKIE;
        $data = explode('-', $pid);
        $id = $data[0];

        if (!is_numeric($id)) {
            throw new \Exception('Invalid user ID in auth cookie');
        }

        # if (!preg_match('^[0-9]+$', $data[0])) return; 
        return (int)$id;
    }

    # function in() {
    #     session_start();
    #     $id = $_SESSION['id'] ?? null;
    #     $hash = $_SESSION['hash'] ?? null;
    #     session_commit();
    #     $hashHit = $hash === hash('md5', $_SERVER['HTTP_USER_AGENT'] ?? 'ua');

    #     if ($id !== null && $hashHit) {
    #         return true; 
    #     }

    #     if (!isset($_COOKIE['pid'])) {
    #         return false;
    #     }

    #     $cookie = $_COOKIE['pid'];
    #     $data = explode('-', $cookie);
    #     $id = $data[0] ?? null;
    #     $hash = $data[1];
    #     $random = $this->get()['random'];
    #     $hashHit = $hash === hash('sha256', $id . $random);

    #     if ($id !== null && $hashHit) {
    #         return true;
    #     }
    # }
}
