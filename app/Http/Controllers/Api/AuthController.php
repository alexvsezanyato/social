<?php

namespace App\Http\Controllers\Api;

use App\Services\User;
use App\Services\App;

class AuthController
{
    public function login()
    {
        $login = $_POST['login'] ?? null;
        $password = $_POST['password'] ?? null;

        $pdo = connect();

        $statement = $pdo->prepare('select * from "user" where "login"=?');
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
            $statement = $pdo->prepare('update "user" set "random"=? where "id"=?');
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
        $statement = $pdo->prepare('select COUNT(*) AS "count" from "user" where "login"=?');
        $statement->execute([$login]);
        $count = $statement->fetch()['count'];

        if ($count != '0') { 
            echo '5';
            return;
        }
        $query = 'insert into "user"("login", "age", "hash", "salt") values (?, ?, ?, ?)';
        $pdo->prepare($query)->execute([$login, $age, $hash, $salt]);
        echo '0';
    }

    public function redirect(User $user)
    {
        if ($user->in()) { 
            header('Loction: /');
            exit;
        }
    }

    public function session()
    {
        session_start();
        echo 'cid: ' . $_SESSION['cid'];

        // $cvcount = $_COOKIE['vcount'] ?? 0;
        // setcookie('vcount', $cvcount, strtotime('+30 days'));

        if (!array_key_exists('cid', $_SESSION)) {
            $_SESSION['cid'] = 1;
        }

        $pdo = connect();

        if ($pdo) {
            // .
            echo 'Success';
        } else {
            echo 'Fail';
            die();
        }

        $statement = $pdo->query('select value from tdb where id=' . ($_SESSION['sid'] ?? null));
        if ($statement) $row = $statement->fetch();
        else $row = [];
        $pdo->query('update tdb set value=' . ++$row['value'] . ' where id=' . ($_SESSION['sid'] ?? null));
        echo ++$row['value'] ?? '';
        phpinfo();
        session_write_close();
    }
}
