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
        header('location: /');
        exit;
    }
}

?>
<header>
    <h1>news</h1>
    <?php if (userLoggedIn()) : ?>
        <div class="button">
            <a href="submit.php">submit post</a>
        </div>
    <?php endif; ?>
</header>
<?php if (isset($_SESSION['messages'])) : ?>
    <?php foreach ($_SESSION['messages'] as $message) : ?>
        <p class="error"><?= $message ?></p>
    <?php endforeach; ?>
    <?php unset($_SESSION['messages']); ?>
<?php endif; ?>
<main>
    <section class="search-field">
        <form action="/view.php" method="get">
            <label for="query">search</label>
            <input type="hidden" name="view" id="view" value="search">
            <input type="text" name="query" id="query">
            <button type="submit">search news</button>
        </form>
    </section>
    <section class="posts">
        <?php foreach ($posts as $post) : ?>
            <article class="post">
                <div class="votes">
                    <?php if (userLoggedIn() && userUpvote($_SESSION['user']['id'], $post['id'], $db)) : ?>
                        <button class="vote active" data-post="<?= $post['id'] ?>">▲</button>
                    <?php elseif (userLoggedIn() && !userUpvote($_SESSION['user']['id'], $post['id'], $db)) : ?>
                        <button class="vote" data-post="<?= $post['id'] ?>">▲</button>
                    <?php else : ?>
                        <!-- control data in js? to escape the button-problem? -->
                        <button class="vote">▲</button>
                    <?php endif; ?>
                    <p class="upvotes"><?= $post['upvotes'] ?></p>
                </div>
                <div class="content">
                    <a href="<?= $post['url'] ?>">
                        <h2><?= $post['title'] ?></h2>
                    </a>
                    <a href="/view.php?view=post&post_id=<?= $post['id'] ?>">
                        <?= $post['comments'] ?> comments
                    </a>
                    <p> posted by
                        <a href="/view.php?view=profile&user_id=<?= $post['user_id'] ?>">
                            <?= fetchPoster($post['user_id'], $db) ?>
                        </a>
                        <?= getAge($post['creation_time']) ?>
                    </p>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
    <?php if ($page > 0) : ?>
        <div class="button">
            <a href="index.php?page=<?= $page - 1 ?>&order_by=<?= $order ?>">previous page</a>
        </div>
    <?php endif; ?>
    <?php if (sizeof($posts) === 10) : ?>
        <div class="button">
            <a href="index.php?page=<?= $page + 1 ?>&order_by=<?= $order ?>">next page</a>
        </div>
    <?php endif; ?>
</main>

<?php
require __DIR__ . '/views/footer.php';
?>