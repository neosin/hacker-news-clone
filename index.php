<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (isset($_GET['page'])) {
    (int)$offset = filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT);
    $posts = fetchPosts($offset, $db);
} else {
    $posts = fetchPosts(0, $db);
}

?>

<main>
    <section>
        <h1>crack news</h1>
        <?php if (isset($_SESSION['user'])) : ?>
            <button><a href="">submit post</a></a></button>
        <?php endif; ?>
    </section>
    <section>
        <?php foreach ($posts as $post) : ?>
            <article>
                <a href="<?= $post['url'] ?>">
                    <h2><?= $post['title'] ?></h2>
                </a>
                <p><?= $post['description'] ?></p>
                <div>
                    <button>upvote</button>
                    <p><?= $post['upvotes'] ?></p>
                    <button>downvote</button>
                </div>
                <p>comments: <?= $post['comments'] ?></p>
                <p>posted by <?= fetchPoster($post['user_id'], $db) ?></p>
            </article>
        <?php endforeach; ?>
    </section>
</main>

<?php
require __DIR__ . '/views/footer.php';
?>