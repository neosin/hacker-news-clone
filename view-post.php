<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (isset($_GET['post_id'])) {
    $postId = (int)filter_var($_GET['post_id'], FILTER_SANITIZE_NUMBER_INT);
    $post = fetchPost($postId, $db);
    $comments = fetchComments($postId, $db);
} else {
    header('location: /index.php');
}

?>

<main>
    <section>
        <?php if (isset($post)) : ?>
            <article>
                <a href="<?= $post['url'] ?>">
                    <h1><?= $post['title'] ?></h1>
                </a>
                <p><?= $post['description'] ?></p>
                <p>by <?= fetchPoster((int)$post['user_id'], $db) ?></p>
                <section class="comments">
                    <?php if (isset($comments)) : ?>
                        <?php foreach ($comments as $comment) : ?>
                            <div class="comment">
                                <a href="/profile.php?user=<?= $comment['user_id'] ?>"><?= fetchPoster((int)$comment['user_id'], $db) ?></a>
                                <p><?= $comment['comment'] ?></p>
                                <?php if (userLoggedIn() && $_SESSION['user']['id'] === $comment['user_id']) : ?>
                                    <a href="/edit.php?edit=comment&comment_id=<?= $comment['id'] ?>">edit comment</a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>No comments yet</p>
                    <?php endif; ?>
                </section>
                <?php if (userLoggedIn()) : ?>
                    <form action="/app/posts/comment.php" method="post">
                        <label for="comment">comment</label>
                        <input type="hidden" id="post_id" name="post_id" value="<?= $post['id'] ?>">
                        <textarea id="comment" name="comment" rows="4"></textarea>
                        <button type="submit">submit</button>
                    </form>
                <?php else : ?>
                    <a href="/login.php">login to comment</a>
                <?php endif; ?>
            </article>
        <?php else : ?>
            <p>The link seems to be dead</p>
        <?php endif; ?>
    </section>
    <?php if (isset($_SESSION['messages'])) : ?>
        <?php foreach ($_SESSION['messages'] as $message) : ?>
            <p><?= $message ?></p>
        <?php endforeach; ?>
        <?php unset($_SESSION['messages']); ?>
    <?php endif; ?>
</main>

<?php
require __DIR__ . '/views/footer.php';
?>