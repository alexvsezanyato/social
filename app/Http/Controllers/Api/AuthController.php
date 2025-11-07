<?php

namespace App\Http\Controllers\Api;

class AuthController
{
    public function login()
    {
        $login = $_POST['login'] ?? null;
        $password = $_POST['password'] ?? null;

        $pdo = connect();

        $statement = $pdo->prepare('SELECT * FROM "user" WHERE "login"=?');
        $statement->execute([$login]);
        $user = $statement->fetch();

        if (!$user) {
            echo '1';
            return;
        }

        $salt = $user['salt'];
        $hash = hash('sha512', $password . $salt);

        if ($hash != $user['hash']) {
            echo '1';
            return;
        }

        session_start();
        $_SESSION['id'] = $user['id'];
        $_SESSION['hash'] = hash('md5', $_SERVER['HTTP_USER_AGENT'] ?? 'ua');
        $random = $user['random'] ?? base64_encode(random_bytes(256 * 0.6666));
        $id = $user['id'];
        $cookie = $id . '-' . hash('sha256', $id . $random); 

        if (!isset($user['random'])) {
            $statement = $pdo->prepare('UPDATE "user" SET "random"=? WHERE "id"=?');
            $r = $statement->execute([$random, $id]);

            if (!$r) {
                unset($_SESSION['id']);
                unset($_SESSION['hash']);
                echo '2';
                return;
            }
        }

        setcookie('pid', $cookie, [
            'expires' => time() + 60*60*24*365, 
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        echo '0';
    }

    public function logout()
    {
        session_start();
        session_unset();
        setcookie('pid', '', time() - 1, '/');
        header('Location: /login.php');
        session_commit();
    }

    public function register()
    {
        $login = trim($_POST['login']) ?? null;
        $password = trim($_POST['password']) ?? null;
        $prepeat = trim($_POST['prepeat']) ?? null;
        $age = trim($_POST['age']) ?? null;

        if (strlen($login) < 3) {
            echo '1';
            return;
        }
        if ($password != $prepeat) {
            echo '2';
            return;
        }
        if (strlen($password) < 6) { 
            echo '3';
            return;
        }
        if (!is_numeric($age) || (int) $age < 14) {
            echo '4';
            return;
        }

        $salt = base64_encode(random_bytes(32*0.66));
        $hash = hash('sha512', $password . $salt);
        $pdo = connect();
        $statement = $pdo->prepare('SELECT COUNT(*) AS "count" FROM "user" WHERE "login"=?');
        $statement->execute([$login]);
        $count = $statement->fetch()['count'];

        if ($count != '0') { 
            echo '5';
            return;
        }

        $query = 'INSERT INTO "user"("login", "age", "hash", "salt") VALUES (?, ?, ?, ?)';
        $pdo->prepare($query)->execute([$login, $age, $hash, $salt]);
        echo '0';
    }
}
