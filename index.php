<?php 
require_once __DIR__ . "/app/user.php";
require_once __DIR__ . "/auth/connect.php";
require_once __DIR__ . "/app/posts.php";
?>
<!doctype html>
<html>
<head>
    <title>Main</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/new-post.css">
    <link rel="stylesheet" href="font-awesome/css/all.css">
</head>

<body>
    <div class="wrapper">
        <header>
            <div class="wrapper">
                <div>
                    <a href="/index.php">News</a>
                </div>
                <div>
                    <ul>
                        <?php if (User::in()): ?>
                        <li><?php echo User::get()['login']; ?></li>
                        <li><a href="/home.php">Home page</a></li>
                        <li><a href="/settings.php">Settings</a></li>
                        <?php else: ?>
                        <li><a href="/login.php">Log in</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </header>
    </div>
</body>
</html>

