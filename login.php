<?php require_once __DIR__ . '/auth/redirect.php' ?>
<html>
<head>
    <title>Log in</title>
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="css/notifications.css">
</head>
<body>
    <div id="register-form"> 
        <div class="title">Register</div> 
        <div class="fieldset">
            <div class="row"><input id="rf-login" required autocomplete="username" type="text" placeholder="Login"></div>
            <div class="row"><input id="rf-password" required autocomplete="new-password" type="password" placeholder="Password"></div>
            <div class="row"><input id="rf-submit" type="submit" value="Go!"></div>
            <script>
                let submit = document.getElementById("rf-submit")
                let login = document.getElementById("rf-login")

                submit.addEventListener('click', (e) => {
                    let password = document.getElementById("rf-password")
                    let request = new XMLHttpRequest()

                    request.addEventListener('readystatechange', (e) => {
                        let state = request.readyState
                        let status = request.status
                        let text = request.responseText
                        if (state != 4) return
                        console.log(text)

                        if (status != 200) {
                            new notification(
                                'Oops, something went wrong. Status: ' + request.status
                            )
                        }

                        switch (text) {
                        case '1':
                            new notification(
                                'Login or password is incorrect'
                            ) 
                            break

                        case '2': 
                            new notificaiton(
                                'Try again later'
                            )
                            break

                        case '0': 
                            console.log('success')
                            window.location = '/' 
                            break
                        
                        default: 
                            console.log(text)
                            return
                        } 
                    }) 
                    request.open('post', 'auth/login.php', true)
                    let data = 'login=' + login.value
                    data += '&password=' + password.value
                    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
                    request.send(data)
                })
            </script>

            <?php require __DIR__ . '/blocks/notifications.php'; ?>
        </div>
    </div>
    <div class="log-in"><a class="link" href="/index.php">News</a> or <a class="link" href="/register.php">register</a></div>
</body>
</html>
