<?php
require_once __DIR__ . "/auth/connect.php";
require_once __DIR__ . "/app/users.php";
require_once __DIR__ . "/app/posts.php";
require_once __DIR__ . "/app/documents.php"; 
?>
<!doctype html>
<html>
<head>
    <title>Home page</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/new-post.css">
    <link rel="stylesheet" href="font-awesome/css/all.css">
    <link rel="stylesheet" href="css/notifications.css">
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
                        <?php if (Users::in()): ?>
                        <li><?= Users::get()['login'] ?></li>
                        <li><a href="/home.php">Home page</a></li>
                        <li><a href="/settings.php">Settings</a></li>
                        <?php else: ?>
                        <li><a href="/login.php">Log in</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </header>
        <main>

            <!-- Notifications -->
            <?php require __DIR__ . '/blocks/notifications.php'; ?>

        </main>
    </div>
</body>
</html>
