<?php 
require_once __DIR__ . "/app/user.php";
require_once __DIR__ . "/auth/connect.php";
require_once __DIR__ . "/app/posts.php";
?>
<!doctype html>
<html>
<head>
    <title>Home page</title>
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
        <main>
            <?php if (!!!User::get()['public']): ?>
            <h3>Profile</h3>
            <ul class="profile-header">
                <li><div class="title">Login: </div><div class="value"><?php echo User::get()['login']; ?></div></li>
                <li><div class="title">Age: </div><div class="value"><?php echo User::get()['age']; ?></div></li>
            </ul>
            <ul class="settings unmarked">
                <h3 class="category">Profile settings</h3>
                <li>
                    <div class="description">Public name</div>
                    <div class="input"><label><div class="label-description">Type here: </div><input id="pn-input" placeholder="..." value="<?php echo User::get()['public']; ?>"></label></div>
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
                <li><div class="title">Name: </div><div class="value"><?php echo User::get()['public']; ?></div></li>
                <li><div class="title">Login: </div><div class="value"><?php echo User::get()['login']; ?></div></li>
                <li><div class="title">Age: </div><div class="value"><?php echo User::get()['age']; ?></div></li>
            </ul>
            <?php endif; ?>
            <div class="posts">
                <div class="posts-header">
                    <h3>Posts</h3>
                    <?php if (User::get()['public']): ?>
                    <div class="new-post"><input id="np-btn" type="button" value="New"></div>
                    <?php endif; ?>
                </div>
                <?php if (!User::get()['public']): ?>
                <hr>
                <div class="notice">You must have public name to post</div>
                <?php elseif (!Posts::exists(User::get()['id'])): ?>
                <hr>
                <div class="notice">You have no post</div>
                <?php else: ?>
                <div class="posts">
                    <?php while ($post = Posts::fetch(User::get()['id'])): ?> 
                    <div class="post">
                        <div class="title">
                            <div class="user"><a href="#"><?= User::get()['public'] ?></a></div>
                            <div class="datetime"> 
                                <div class="date"><?= $post['date'] ?></div>
                                <div class="time"><?= $post['time'] ?></div>
                            </div>
                        </div>
                        <div class="data"><?=$post['text']?></div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php if (User::get()['public']): ?>
    <!--new-post-block-->
    <div id="np-blockjs" class="np-block" style="visibility: hidden">
        <div class="wrapper">
            <div id="np-window" class="window">
                <div class="header">
                    <div>New post</div>
                    <button id="np-close"><i class="fas fa-times"></i></button>
                </div>
                <textarea id="np-textarea"></textarea>
                <input typ
                <input type="file">
                <input id="np-post" class="post-do" type="button" value="Post">
            </div>
        </div>
    </div>
    <script src="/scripts/new-post.js"></script>
    <?php endif; ?>

</body>
</html>
