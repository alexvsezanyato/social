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

            <?php if (!Users::get()['public']): ?>
            <h3>Profile</h3>
            <ul class="profile-header">
                <li class="ph-row">
                    <div class="title">Login: </div>
                    <div class="value"><?php echo Users::get()['login']; ?></div>
                </li>
                <li class="ph-row">
                    <div class="title">Age: </div>
                    <div class="value"><?php echo Users::get()['age']; ?></div>
                </li>
            </ul>
            <ul class="settings unmarked">
                <h3 class="category">Profile settings</h3>
                <li>
                    <div class="description">Public name</div>
                    <div class="input"><label><div class="label-description">Type here: </div><input id="pn-input" placeholder="..." value="<?php echo Users::get()['public']; ?>"></label></div>
                    <div class="notice">You must have public name to post or to comment.</div>
                    <script defer src="scripts/settings-logout.js"></script>
                </li>
                <ul class="apply-settings unmarked" style="margin-bottom: 20px;">
                    <li><input id="as-apply" data-reload type="button" value="Apply"></li>
                </ul>
            </ul>
            <?php else: ?>
            <h3 style="padding-top: 2px;">Profile</h3>
            <ul class="profile-header">
                <li class="ph-row">
                    <div class="title">Name: </div>
                    <div class="value"><?php echo Users::get()['public']; ?></div>
                </li>
                <li class="ph-row">
                    <div class="title">Login: </div>
                    <div class="value"><?php echo Users::get()['login']; ?></div>
                </li>
                <li class="ph-row">
                    <div class="title">Age: </div>
                    <div class="value"><?php echo Users::get()['age']; ?></div>
                </li>
            </ul>
            <?php endif; ?>

            <div class="posts">
                <div class="posts-header">
                    <h3>Posts</h3>
                    <?php if (Users::get()['public']): ?>
                    <div class="new-post"><input id="np-btn" type="button" value="New"></div>
                    <?php endif; ?>
                </div>
                <?php if (!Users::get()['public']): ?>
                <hr>
                <div class="notice">You must have public name to post</div>
                <?php elseif (!Posts::exists(Users::get()['id'])): ?>
                <hr>
                <div class="notice">You have no post</div>
                <?php else: ?>
                <div id="post-list" class="posts">

                    <!-- Posts -->
                    <?php while ($post = Posts::fetch(Users::get()['id'])): ?> 
                    <?php require __DIR__ . '/blocks/post.php'; ?>
                    <?php endwhile; ?>
                    
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- New post -->
    <?php if (Users::get()['public']): ?>
    <?php require __DIR__ . '/blocks/new-post.php'; ?>
    <?php endif; ?>

    <!-- Scripts -->
    <script src="/scripts/posts.js"></script>

</body>
</html>
