<?php

use App\Services\User;
use App\Services\App;

if (User::in()) { 
    App::redirect("/");
    die;
}

?>

<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/auth.css">
</head>
<body>
    <div id="register-form"> 
        <div class="title">Register</div> 
        <div class="fieldset">
            <div class="row"><input id="rf-login" required autocomplete="off" type="text" placeholder="Login"></div>
            <div class="row"><input id="rf-password" required autocomplete="new-password" type="password" placeholder="Password"></div>
            <div class="row"><input id="rf-prepeat" required autocomplete="new-password" type="password" placeholder="Repeat password"></div>
            <div class="row"><input id="rf-age" required type="text" placeholder="Age"></div>
            <div class="row"><input id="rf-submit" type="submit" value="Go!"></div>
            <script>
                let submit = document.getElementById("rf-submit");
                let age = document.getElementById("rf-age");
                let login = document.getElementById("rf-login");

                age.addEventListener('input', (e) => {
                    switch (age.value.slice(-1)) {
                    case '1': case '2': case '3': 
                    case '4': case '5': case '6': 
                    case '7': case '8': case '9': 
                    case '0': 
                        if (parseInt(age.value) <= 99) break;
                    default: 
                        age.value = age.value.slice(0, age.value.length - 1);
                    }
                });
                login.addEventListener('input', (e) => {
                    switch (login.value.slice(-1)) {
                    case ' ': case '+': case '=': 
                    case '\\': case '|': case '*':
                    case '&': case '^': case '%':
                    case '$': case '!': case '@':
                    case '#': case ';': case ':':
                    case '<': case '>': case '?': 
                    case '[': case ']': case '{':
                    case '}': case '\'': case '"':
                    case '~': case '`': case '/':
                    case '.': case ',': 
                        login.value = login.value.slice(0, age.value.length - 1);
                    }
                });
                submit.addEventListener('click', (e) => {
                    let password = document.getElementById("rf-password");
                    let rpassword = document.getElementById("rf-prepeat");
                    let request = new XMLHttpRequest();

                    request.addEventListener('readystatechange', (e) => {
                        switch (request.readyState) {
                        case 4:
                            if (request.status == 200) {
                                switch (request.responseText) {
                                case '1':
                                    alert('The login length must be 3 at least'); 
                                    break;
                                case '2': 
                                    alert('Password mismatch');
                                    break;
                                case '3': 
                                    alert('The password length must be 6 at least');
                                    break;
                                case '4': 
                                    alert('Unacceptable age. Must be 14 at least');
                                    break;
                                case '0': 
                                    alert('Registered. Log in now');
                                    break;
                                case '5': 
                                    alert('Login or password already in use');
                                    break;
                                case '9': 
                                    alert('Not registered. Try again');
                                    break;
                                }
                            } else {
                                alert('Oops, something went wrong. Status: ' + request.status);
                            }
                            break;
                        }
                    }); 
                    request.open('post', '/register', true);
                    let data = 'login=' + login.value;
                    data += '&password=' + password.value;
                    data += '&prepeat=' + rpassword.value;
                    data += '&age=' + age.value;
                    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    request.send(data);
                });
            </script>
        </div>
    </div>
    <div class="log-in"><a class="link" href="/">News</a> or <a class="link" href="/login">log in</a></div>
</body>
</html>
