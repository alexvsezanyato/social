<?php

use App\Services\Users;

/**
 * @var Users $users
 */

?>

<!doctype html>
<html>
<head>
    <title>Home page</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/new-post.css">
    <link rel="stylesheet" href="/font-awesome/css/all.css">
    <link rel="stylesheet" href="/css/notifications.css">
    <link rel="stylesheet" href="/css/sets.css">
</head>

<body>
<div class="wrapper">

    <!-- Header --> 
    <?php require VIEW_DIR.'/layout/header.php'; ?>
    <main>
    <!-- Notifications -->
    <?php require VIEW_DIR.'/layout/notifications.php'; ?>

    <h3 style="padding-top: 2px;">Profile</h3>
    <ul class="profile-header">
        <li class="ph-row">
            <div class="title">Login: </div>
            <div class="value"><?php echo $users->get()['login']; ?></div>
        </li>
        <li class="ph-row">
            <div class="title">Age: </div>
            <div class="value"><?php echo $users->get()['age']; ?></div>
        </li>
    </ul>
    <div class="sets">
        <h3 class="title">Profile settings</h3>
        <div class="group">
            <div class="group-item">
                <label class="input">
                    <div class="set-name">Public name: </div>
                    <input 
                        placeholder="..." 
                        value="<?= $users->get()['public'] ?>"
                    >
                </label>
                <div class="set-desc">You must have public name to post or to comment.</div>
            </div>
        </div>
        <button class="apply" data-reload>Apply</button>
    </div>
    </main>
</div>

<script src="/scripts/profile-settings.js"></script>
</body>
</html>
