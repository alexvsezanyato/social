<?php 
require_once __DIR__ . '/../app/documents.php';
$docs = new Documents();
$user = Users::get();
$docs->user($user);
$docs->pid($post['id']);
$docs = $docs->get();

require_once __DIR__ . '/../app/pictures.php';
$pics = new Pictures();
$pics->user($user);
$pics->pid($post['id']);
$pics = $pics->get();
?>

<div class="post" data-id="<?= $post['id'] ?>">
    <div class="title">
        <div class="user"><a href="#"><?= Users::get()['public'] ?></a></div>
        <div class="right">
            <div class="datetime"> 
                <div class="date"><?= $post['date'] ?> at</div>
                <div class="time"><?= $post['time'] ?></div>
            </div>
            <div class="menu" data-menu><i class="fas fa-caret-down" data-menu></i></div>
        </div>
    </div>
    <div class="data"><?=$post['text']?></div>

    <?php if ($pics->rowCount()): ?>
    <ul class="file-list">
        <li class="file-block">
            <div class="file-name"><?= $pics->rowCount() ?> pictures</div>
        </li>

        <?php while ($file = $pics->fetch()): ?>
        <li class="file-block">
            <div class="file-name">
                <i class="fas fa-file"></i>
                <a href="download.php?id=<?= $file['source'] ?>&name=<?= $file['name'] ?>&type=<?= $file['mime'] ?>" download><?= $file['name'] ?></a>
            </div>
        </li>
        <?php endwhile; ?>
    </ul>
    <?php else: ?> <div>PIDOR</div>
    <?php endif; ?>

    <?php if ($docs->rowCount()): ?>
    <ul class="file-list">
        <li class="file-block">
            <div class="file-name"><?= $docs->rowCount() ?> documents</div>
        </li>

        <?php while ($file = $docs->fetch()): ?>
        <li class="file-block">
            <div class="file-name">
                <i class="fas fa-file"></i>
                <a href="download.php?id=<?= $file['source'] ?>&name=<?= $file['name'] ?>&type=<?= $file['mime'] ?>" download><?= $file['name'] ?></a>
            </div>
        </li>
        <?php endwhile; ?>
    </ul>
    <?php endif; ?>

</div>
