<?php
$login = $_POST['login'] ?? null;
$password = $_POST['password'] ?? null;
$prepeat = $_POST['prepeat'] ?? null;
$age = $_POST['age'] ?? null;
check();

function check() {
    global $login;
    global $password;
    global $prepeat;
    global $age;

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
    main();
    return;
}
function main() {
    global $login;
    global $password;
    global $age;
    // data is valid, do main
    // ..

    
    echo '0';
    return;
}

