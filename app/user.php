<?php

class User {
    static function get() {
        if (self::$user !== null) return self::$user;
        require_once __DIR__ . '/../auth/connect.php';
        $pdo = connect();
        $statement = $pdo->prepare("select * from users where id=?");
        $result = $statement->execute([self::id()]);
        if ($result) $statement = $statement->fetch();
        return $statement ? $statement : [];
    }

    static function id() {
        if (self::$id !== null) return self::$id;
        session_start();
        $id = $_SESSION['id'] ?? null;
        session_commit();

        if ($id !== null) {
            self::$id = $id;
            return self::$id;
        }

        if (!isset($_COOKIE['pid'])) return;
        $cookie = $_COOKIE['pid'];
        $data = explode('-', $cookie);
        // if (!preg_match('^[0-9]+$', $data[0])) return; 
        self::$id = $data[0];
        return self::$id;
    }

    static function in() {
        if (self::$in !== null) return self::$in;
        session_start();
        $id = $_SESSION['id'] ?? null;
        $hash = $_SESSION['hash'] ?? null;
        session_commit();
        $hashHit = $hash === hash('md5', $_SERVER['HTTP_USER_AGENT'] ?? 'ua');
        if ($id !== null && $hashHit) return true; 

        // session doesn't exist or itn't valid 
        if (!isset($_COOKIE['pid'])) return false;
        $cookie = $_COOKIE['pid'];
        $data = explode('-', $cookie);
        $id = $data[0] ?? null;
        $hash = $data[1];
        $random = self::get()['random'];
        $hashHit = $hash === hash('sha256', $id . $random);
        if ($id !== null && $hashHit) return true;
    }

    static private $user = null;
    static private $in = null;
    static private $id = null;
}
