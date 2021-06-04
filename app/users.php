<?php

class Users {
    static function get(): iterable {
        if (self::$user !== null) return self::$user;
        require_once __DIR__ . '/../auth/connect.php';
        $pdo = connect();
        $statement = $pdo->prepare("select * from users where id=?");
        $result = $statement->execute([self::id()]);
        if ($result) $statement = $statement->fetch();
        return $statement ? $statement : [];
    }

    static function id(): ?int {
        if (self::$id !== null) return self::$id;
        session_start();
        $id = $_SESSION['id'] ?? null;
        session_commit();

        if ($id !== null) {
            self::$id = $id;
            return self::$id;
        }

        if (!isset($_COOKIE['pid'])) {
            self::$id = null;
            return null;
        }

        $cookie = $_COOKIE['pid'];
        $data = explode('-', $cookie);
        if (!preg_match('^[0-9]+$', $data[0])) return null; 
        self::$id = $data[0];
        return self::$id;
    }

    static function in(): bool {
        if (self::$in !== null) return self::$in;
        session_start();
        $id = $_SESSION['id'] ?? null;
        $hash = $_SESSION['hash'] ?? null;
        session_commit();

        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $hashHit = ($hash === hash('md5', $userAgent ?? 'agent'));
        if ($id !== null && $hashHit) return self::$in = true; 

        // session doesn't exist or itn't valid 
        if (!isset($_COOKIE['pid'])) return self::$in = false;
        $cookie = $_COOKIE['pid'];
        $data = explode('-', $cookie);
        $id = $data[0] ?? null;
        $hash = $data[1];
        $random = self::get()['random'];
        $hashHit = ($hash === hash('sha256', $id . $random));
        if ($id !== null && $hashHit) return self::$id = true;
    }

    static private ?PDOStatement $user = null;
    static private ?bool $in = null;
    static private ?int $id = null;
}
