<?php

namespace App\Services;

class User {
    private $user = null;
    private $in = null;
    private $id = null;

    public function get() {
        $pdo = connect();
        $statement = $pdo->prepare('SELECT * FROM "user" WHERE "id"=?');
        $result = $statement->execute([$this->id()]);

        if ($result) {
            $statement = $statement->fetch();
        }

        return $statement ?: [];
    }

    public function id() {
        session_start();
        $id = $_SESSION['id'] ?? null;
        session_commit();

        if ($id !== null) {
            $this->id = $id;
            return $this->id;
        }

        if (!isset($_COOKIE['pid'])) {
            return;
        }

        $cookie = $_COOKIE['pid'];
        $data = explode('-', $cookie);
        // if (!preg_match('^[0-9]+$', $data[0])) return; 
        $this->id = $data[0];
        return $this->id;
    }

    function in() {
        session_start();
        $id = $_SESSION['id'] ?? null;
        $hash = $_SESSION['hash'] ?? null;
        session_commit();
        $hashHit = $hash === hash('md5', $_SERVER['HTTP_USER_AGENT'] ?? 'ua');

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
        $hashHit = $hash === hash('sha256', $id . $random);

        if ($id !== null && $hashHit) {
            return true;
        }
    }
}
