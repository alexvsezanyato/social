<header>
<div class="wrapper">
    <div>
        <a href="/index.php">News</a>
    </div>
    <div>
        <ul>
            <?php if ($in): ?>
                <li><?= $user['login'] ?></li>
                <li><a href="/home.php">Home page</a></li>
                <li><a href="/settings.php">Settings</a></li>
            <?php else: ?>
                <li><a href="/login.php">Log in</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
</header>
