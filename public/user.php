
<?php require_once __DIR__ . "/auth/connect.php"; ?>
<?php require_once __DIR__ . "/app/users.php"; ?>
<?php require_once __DIR__ . "/app/posts.php"; ?>
<?php require_once __DIR__ . "/app/documents.php"; ?>

<!doctype html>
<html>
<head>
    <title>Home page</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/new-post.css">
    <link rel="stylesheet" href="font-awesome/css/all.css">
    <link rel="stylesheet" href="css/notifications.css">
    <link rel="stylesheet" href="css/sets.css">
</head>

<body>
<div class="wrapper">

    <!-- Header --> 
    <?php require __DIR__ . '/blocks/header.php'; ?>
    <main>
    <!-- Notifications -->
    <?php require __DIR__ . '/blocks/notifications.php'; ?>

    <h3 style="padding-top: 2px;">Profile</h3>
    <ul class="profile-header">
        <?php if (Users::get()['public']): ?>
        <li class="ph-row">
            <div class="title">Name: </div>
            <div class="value"><?php echo Users::get()['public']; ?></div>
        </li>
        <?php endif; ?>
        <li class="ph-row">
            <div class="title">Login: </div>
            <div class="value"><?php echo Users::get()['login']; ?></div>
        </li>
        <li class="ph-row">
            <div class="title">Age: </div>
            <div class="value"><?php echo Users::get()['age']; ?></div>
        </li>
    </ul>

    <div class="posts">
        <div class="posts-header">
            <h3>Posts</h3>
        </div>

        <div id="post-list" class="posts">
            <?php if (!Users::get()['public']): ?>
            <hr class="hr"><div class="notice posts-end">You must have public name to post</div>
            <?php else: ?>
            <script src="/scripts/posts.js"></script>
            <?php endif; ?>
        </div>
    </div>
    </main>
</div>

<!-- New post -->
<?php
if (Users::get()['public']):
require __DIR__ . '/blocks/new-post.php';
endif;
?>

</body>
</html>
