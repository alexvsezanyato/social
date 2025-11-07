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
            <li><a href="/home">Home page</a></li>
            <li><a href="/settings">Settings</a></li>
        <?php else: ?>
            <li><a href="/login">Log in</a></li>
        <?php endif; ?>
        </ul>
    </div>
</header>
