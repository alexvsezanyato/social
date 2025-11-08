<?php

namespace App\Http\Controllers\Api;

use App\Services\Auth;
use App\Services\Database;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    public function login(Database $database)
    {
        $login = $_POST['login'] ?? null;
        $password = $_POST['password'] ?? null;

        $statement = $database->connection->prepare(
            <<<SQL
            SELECT * FROM "user" WHERE "login"=:login
            SQL
        );

        $statement->bindParam('login', $login);
        $statement->execute();
        $user = $statement->fetch();

        if (!$user) {
            return new Response(1);
        }

        $salt = $user['salt'];
        $hash = hash('sha512', $password . $salt);

        if ($hash != $user['hash']) {
            return new Response(1);
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
                UPDATE "user" SET "random"=:random WHERE "id"=:id
                SQL
            );

            $statement->bindParam('random', $random);
            $statement->bindParam('id', $id);
            $result = $statement->execute();

            if (!$result) {
                unset($_SESSION['id']);
                unset($_SESSION['hash']);
                return new Response(2);
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

        return new Response(0);
    }

    public function logout(Auth $auth)
    {
        $auth->logout();
        return new Response(0);
    }

    public function register(Database $database)
    {
        $login = trim($_POST['login'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $prepeat = trim($_POST['prepeat'] ?? '');
        $age = trim($_POST['age'] ?? '');

        if (strlen($login) < 3) {
            return new Response(content: 1);
        }

        if ($password != $prepeat) {
            return new Response(content: 2);
        }

        if (strlen($password) < 6) { 
            return new Response(content: 3);
        }

        if (!is_numeric($age) || (int) $age < 14) {
            return new Response(content: 4);
        }

        $salt = base64_encode(random_bytes(32*0.66));
        $hash = hash('sha512', $password . $salt);

        $statement = $database->connection->prepare(
            <<<SQL
            SELECT COUNT(*) AS "count"
            FROM "user"
            WHERE "login"=:login
            SQL
        );
        $statement->bindParam(':login', $login);
        $statement->execute();
        $result = $statement->fetch();

        ['count' => $count] = $result;

        if ($count != '0') { 
            return new Response(content: 5);
        }

        $database->connection
            ->prepare(
                <<<SQL
                INSERT INTO "user"("login", "age", "hash", "salt")
                VALUES (:login, :age, :hash, :salt)
                SQL
            )
            ->execute([
                'login' => $login,
                'age' => $age,
                'hash' => $hash,
                'salt' => $salt,
            ]);

        return new Response(content: 0);
    }
}
