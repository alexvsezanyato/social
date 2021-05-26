<?php
$login = tpost('login') ?? null;
$password = tpost('password') ?? null;
$prepeat = tpost('prepeat') ?? null;
$age = tpost('age') ?? null;
check();

function tpost($i) {
    $data = trim($_POST[$i]);
    return $data;
}

function check() {
    global $login;
    global $password;
    global $prepeat;
    global $age;

    if (strlen($login) < 3) {
        echo "$login 1";
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
    main();
    return;
}
function main() {
    global $login;
    global $password;
    global $prepeat;
    global $age;
    // data is valid, do main
    // ..

    $salt = base64_encode(random_bytes(32*0.66));
    $hash = hash('sha512', $password . $salt);
    $pdo = new PDO('mysql:host=localhost;dbname=phpsession;charset=utf8', 'alexsql', 'regular');
    $statement = $pdo->prepare('select COUNT(*) from users where login=?');
    $statement->execute([$login]);
    $count = $statement->fetch()['COUNT(*)'];

    if ($count != '0') { 
        echo '5';
        return;
    }
    $query = "insert into users (login, age, hash, salt) values (?, ?, ?, ?)";
    $pdo->prepare($query)->execute([$login, $age, $hash, $salt]);
    echo '0';
    return;
}

