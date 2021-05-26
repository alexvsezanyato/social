<?php
$login = $_POST['login'] ?? null;
$password = $_POST['password'] ?? null;
check();

function check() {
    global $login;
    global $password;

    main();
    return;
}

function main() {
    global $login;
    global $password;
    require_once 'connect.php';
    $pdo = connect();

    $statement = $pdo->prepare('select * from users where login=?');
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
        $statement = $pdo->prepare('update users set random=? where id=?');
        $r = $statement->execute([$random, $id]);

        if (!$r) {
            unset($_SESSION['id']);
            unset($_SESSION['hash']);
            echo '2';
            return;
        }
    }
    setcookie(
        'pid', 
        $cookie, 
        time() + 60*60*24*365, 
        '/', // path
        '', // domain 
        true, // httponly
        true // secure
    );
    echo '0';
    return;
}

