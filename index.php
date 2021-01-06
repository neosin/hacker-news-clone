<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (isset($_GET['page'])) {
    $page = (int)filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT);
} else {
    $page = 0;
}


if (!isset($_GET['order_by'])) {
    $_GET['order_by'] = 'new';
}

if (isset($_GET['order_by'])) {
    $order = filter_var($_GET['order_by'], FILTER_SANITIZE_STRING);
    if ($order === 'new') {
        $posts = fetchPosts($page, $db);
    } elseif ($order === 'top') {
        $posts = fetchPosts($page, $db, true);
    } else {
        $_GET['order_by'] = 'new';
    }
}

if (userLoggedIn()) {
    $user = $_SESSION['user'];
} else {
    $user = NULL;
}

?>

<main>
    <section>
        <h1>crack news</h1>
        <h2>order by</h2>
        <a href="index.php?order_by=top">upvotes</a>
        <a href="index.php?order_by=new">new</a>
        <br>
        <?php if (userLoggedIn()) : ?>
            <button><a href="submit.php">submit post</a></button>
        <?php endif; ?>
    </section>
    <section>
        <?php foreach ($posts as $post) : ?>
            <article>
                <hr>
                <?php if (userLoggedIn() && userUpvote($user['id'], $post['id'], $db)) : ?>
                    <button class="vote up active" data-post="<?= $post['id'] ?>">upvote</button>
                <?php elseif (userLoggedIn() && !userUpvote($user['id'], $post['id'], $db)) : ?>
                    <button class="vote up" data-post="<?= $post['id'] ?>">upvote</button>
                <?php else : ?>
                    <button><a href="login.php">upvote</a></button>
                <?php endif; ?>
                <p class="upvotes"><?= $post['upvotes'] ?></p>
                <a href="<?= $post['url'] ?>">
                    <h2><?= $post['title'] ?></h2>
                </a>
                <p><?= $post['description'] ?></p>
                <p>posted by <?= fetchPoster($post['user_id'], $db) ?></p>
            </article>
        <?php endforeach; ?>
    </section>
    <a href="index.php?page=<?= $page - 1 ?>&order_by=<?= $order ?>">previous page</a>
    <a href="index.php?page=<?= $page + 1 ?>&order_by=<?= $order ?>">next page</a>
    <?php if (isset($_SESSION['message'])) : ?>
        <p><?= $_SESSION['message'] ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
</main>

<?php
require __DIR__ . '/views/footer.php';
?>