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
        // in() sets id,
        // this one returns only 
        // if something went wrong (in() & not id)
        // then return null

        if (self::$id !== null) return self::$id;
        if (!self::in()) return null;

        if (self::$id === null) return null;
        else return self::$id;
    }

    static function in(): bool {
        if (self::$in !== null) return self::$in;
        session_start();
        $id = $_SESSION['id'] ?? null;
        $hash = $_SESSION['hash'] ?? null;
        session_commit();

        // for session, check user agent for security
        // using hash for security, 
        // so there is no user agent data 
        // in the server
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $hashHit = $hash === hash('md5', $userAgent ?? 'agent');

        if ($id !== null && $hashHit) { 
            self::$id = $id;
            return self::$in = true; 
        }

        // session doesn't exist or itn't valid 
        if (!isset($_COOKIE['pid'])) return self::$in = false;
        $cookie = $_COOKIE['pid'];
        $data = explode('-', $cookie);
        $id = $data[0] ?? null;
        self::$id = $id;
        $hash = $data[1];
        $random = self::get()['random'];
        $hashHit = ($hash === hash('sha256', $id . $random));
        if ($id !== null && $hashHit) return self::$in = true;

        self::$id = null;
        return self::$in = false;
    }

    static private ?PDOStatement $user = null;
    static private ?bool $in = null;
    static private ?int $id = null;
}
