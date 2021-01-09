<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (isset($_GET['view'])) {
    $view = filter_var($_GET['view'], FILTER_SANITIZE_STRING);
    if ($view === 'post') {
        if (isset($_GET['post_id'])) {
            $postId = (int)filter_var($_GET['post_id'], FILTER_SANITIZE_NUMBER_INT);
            $post = fetchPost($postId, $db);
            $postComments = fetchComments($postId, $db);
        }
    } elseif ($view === 'profile') {
        if (isset($_GET['user_id'])) {
            $userId = (int)filter_var($_GET['user_id'], FILTER_SANITIZE_NUMBER_INT);
            if (userLoggedIn() && $userId === (int)$_SESSION['user']['id']) {
                header('location: /profile.php');
                exit;
            }
            $profile = fetchUser($userId, $db);
            $userPosts = fetchUserPosts($userId, $db);
            $userComments = fetchUserComments($userId, $db);
        }
    } else {
        addMessage('Incorrect url');
        header('location: /');
        exit;
    }
} else {
    addMessage('Incorrect url');
    header('location: /');
    exit;
}

?>

<main>
    <?php if (isset($post)) : ?>
        <article>
            <a href="<?= $post['url'] ?>">
                <h1><?= $post['title'] ?></h1>
            </a>
            <p><?= $post['description'] ?></p>
            <p>by <?= fetchPoster((int)$post['user_id'], $db) ?></p>
            <?php if (userLoggedIn() && $post['user_id'] === $_SESSION['user']['id']) : ?>
                <a href="/edit.php?edit=post&post_id=<?= $post['id'] ?>">
                    edit post
                </a>
            <?php endif; ?>
            <section class="comments">
                <?php if (isset($postComments)) : ?>
                    <?php foreach ($postComments as $comment) : ?>
                        <div class="comment">
                            <a href="/view.php?view=profile&user_id=<?= $comment['user_id'] ?>"><?= fetchPoster((int)$comment['user_id'], $db) ?></a>
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
                    <textarea id="comment" name="comment" rows="4" required></textarea>
                    <button type="submit">submit</button>
                </form>
            <?php else : ?>
                <a href="/login.php">login to comment</a>
            <?php endif; ?>
        </article>
    <?php elseif (isset($profile)) : ?>
        <section>
            <?php if (!isset($profile['image_url'])) : ?>
                <img src="/assets/images/no-image.png" alt="no profile picture selected">
            <?php else : ?>
                <img src="<?= $profile['image_url'] ?>" alt="profile picture">
            <?php endif; ?>
            <p><?= $profile['user_name'] ?></p>
            <p><?= $profile['bio'] ?></p>
            <?php if (isset($userPosts)) : ?>
                <h2>posts</h2>
                <ul>
                    <?php foreach ($userPosts as $userPost) : ?>
                        <li>
                            <a href="/view-post.php?post_id=<?= $userPost['id'] ?>"><?= $userPost['title'] ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <h2>no posts</h2>
            <?php endif; ?>
            <?php if (isset($userComments)) : ?>
                <h2>comments</h2>
                <?php foreach ($userComments as $userComment) : ?>
                    <div class="comment">
                        <p>on
                            <a href="/view-post.php?post_id=<?= $userComment['post_id'] ?>">
                                <?= fetchPostTitle((int)$userComment['post_id'], $db) ?>
                            </a>
                        </p>
                        <p><?= $userComment['comment'] ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <h2>no comments</h2>
        </section>
    <?php endif; ?>
<?php endif; ?>
</main>

<?php
require __DIR__ . '/views/footer.php';
?>