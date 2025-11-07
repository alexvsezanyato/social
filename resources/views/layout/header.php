<?php

use App\Services\Users;
use App\Services\App;

$users = App::$instance->container->get(Users::class);

?>

<header>
    <div>
        <a href="/">News</a>
    </div>
    <div>
        <ul>
        <?php if ($users->in()): ?>
            <li><?= $users->get()['login'] ?></li>
            <li><a href="/profile/index">Home page</a></li>
            <li><a href="/profile/settings">Settings</a></li>
            <li><a href="/profile/logout">Logout</a></li>
        <?php else: ?>
            <li><a href="/auth/login">Log in</a></li>
        <?php endif; ?>
        </ul>
    </div>
</header>
