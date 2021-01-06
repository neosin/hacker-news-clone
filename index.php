<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (isset($_GET['page'])) {
    $page = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT);
    $posts = fetchPosts($page, $db);
} else {
    $page = 0;
    $posts = fetchPosts($page, $db);
}
?>

<main>
    <section>
        <h1>crack news</h1>
        <?php if (isset($_SESSION['user'])) : ?>
            <button><a href="submit.php">submit post</a></a></button>
        <?php endif; ?>
    </section>
    <section>
        <?php foreach ($posts as $post) : ?>
            <article>
                <h2><?= $post['id'] ?></h2>
                <button class="vote up" data-post="<?= $post['id'] ?>">upvote</button>
                <a href="<?= $post['url'] ?>">
                    <h2><?= $post['title'] ?></h2>
                </a>
                <p><?= $post['description'] ?></p>
                <p>posted by <?= fetchPoster($post['user_id'], $db) ?></p>
            </article>
        <?php endforeach; ?>
    </section>
    <a href="index.php?page=<?= $page - 1 ?>">previous page</a>
    <a href="index.php?page=<?= $page + 1 ?>">next page</a>
    <?php if (isset($_SESSION['message'])) : ?>
        <p><?= $_SESSION['message'] ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
</main>

<?php
require __DIR__ . '/views/footer.php';
?>