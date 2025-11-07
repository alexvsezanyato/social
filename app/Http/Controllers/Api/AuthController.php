<?php

namespace App\Http\Controllers\Api;

use App\Services\Auth;
use App\Services\Database;

class AuthController
{
    public function login(Database $database)
    {
        $login = $_POST['login'] ?? null;
        $password = $_POST['password'] ?? null;

        $statement = $database->connection->prepare(
            <<<SQL
            SELECT * FROM "user" WHERE "login"=?
            SQL
        );

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
            $statement = $database->connection->prepare(
                <<<SQL
                UPDATE "user" SET "random"=? WHERE "id"=?
                SQL
            );

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

    public function logout(Auth $auth)
    {
        $auth->logout();
    }

    public function register(Database $database)
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

        $statement = $database->connection->prepare(
            <<<SQL
            SELECT COUNT(*) AS "count" FROM "user" WHERE "login"=?
            SQL
        );

        $statement->execute([$login]);
        $count = $statement->fetch()['count'];

        if ($count != '0') { 
            echo '5';
            return;
        }

        $database->connection
            ->prepare(
                <<<SQL
                INSERT INTO "user"("login", "age", "hash", "salt")
                VALUES (?, ?, ?, ?)
                SQL
            )
            ->execute([
                $login,
                $age,
                $hash,
                $salt,
            ]);

        echo '0';
    }
}
