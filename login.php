<?php require_once __DIR__ . '/auth/redirect.php'; ?>
<html>
<head>
    <title>Log in</title>
    <link rel="stylesheet" type="text/css" href="css/auth.css">
</head>
<body>
    <div id="register-form"> 
        <div class="title">Register</div> 
        <div class="fieldset">
            <div class="row"><input id="rf-login" required autocomplete="off" type="text" placeholder="Login"></div>
            <div class="row"><input id="rf-password" required autocomplete="off" type="password" placeholder="Password"></div>
            <div class="row"><input id="rf-submit" autocomplete="off" type="submit" value="Go!"></div>
            <script>
                let submit = document.getElementById("rf-submit");
                let login = document.getElementById("rf-login");

                submit.addEventListener('click', (e) => {
                    let password = document.getElementById("rf-password");
                    let request = new XMLHttpRequest();

                    request.addEventListener('readystatechange', (e) => {
                        switch (request.readyState) {
                        case 4:
                            if (request.status == 200) {
                                switch (request.responseText) {
                                case '1':
                                    alert('Login or password is incorrect'); 
                                    break;
                                case '2': 
                                    alert('Try again later');
                                    break;
                                case '0': 
                                    window.reload = true;
                                    break;
                                }
                            } else {
                                alert('Oops, something went wrong. Status: ' + request.status);
                            }
                            break;
                        }
                    }); 
                    request.open('post', 'auth/login.php', true);
                    let data = 'login=' + login.value;
                    data += '&password=' + password.value;
                    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    request.send(data);
                });
            </script>
        </div>
    </div>
    <div class="log-in"><a class="link" href="/index.php">News</a> or <a class="link" href="/login.php">log in</a></div>
</body>
</html>
