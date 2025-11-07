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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>

<body>
    <?php require VIEW_DIR.'/layout/header.php'; ?>
    <?php require VIEW_DIR.'/layout/notifications.php'; ?>

    <div class="wrapper">
        <main>
            <?php if ($users->get()['public']): ?>
            <h3 style="padding-top: 2px;">Profile</h3>
            <ul class="profile-header">
                <li class="ph-row">
                    <div class="title">Name: </div>
                    <div class="value"><?php echo $users->get()['public']; ?></div>
                </li>
                <li class="ph-row">
                    <div class="title">Login: </div>
                    <div class="value"><?php echo $users->get()['login']; ?></div>
                </li>
                <li class="ph-row">
                    <div class="title">Age: </div>
                    <div class="value"><?php echo $users->get()['age']; ?></div>
                </li>
            </ul>
            <?php elseif ($users->get()): ?>
            <h3>Profile</h3>
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
                                id="public-input" 
                                placeholder="..." 
                                value="<?= $users->get()['public'] ?>"
                            >
                        </label>
                        <div class="set-desc">You must have public name to post or to comment.</div>
                    </div>
                </div>
                <button class="apply" data-reload>Apply</button>
            </div>
            <?php endif; ?>

            <div class="posts">
                <div class="posts-header">
                    <h3>Posts</h3>
                    <?php if ($users->get()['public']): ?>
                    <div class="new-post"><input id="np-btn" type="button" value="New"></div>
                    <?php endif; ?>
                </div>

                <div id="post-list" class="posts">
                    <?php if (!$users->get()['public']): ?>
                    <hr class="hr"><div class="notice posts-end">You must have public name to post</div>
                    <?php else: ?>
                    <script src="/scripts/posts.js"></script>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <?php if ($users->get()['public']): ?><?php require VIEW_DIR.'/components/new-post.php' ?><?php endif ?>
</body>
</html>
