<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (!userLoggedIn()) {
    header("location: /");
    exit;
} else {
    $userPosts = fetchUserPosts($_SESSION['user']['id'], $db); // order by upvotes?
    $userComments = fetchUserComments($_SESSION['user']['id'], $db);
}

?>
<main>
    <?php if (isset($_SESSION['messages'])) : ?>
        <?php foreach ($_SESSION['messages'] as $message) : ?>
            <p class="error"><?= $message ?></p>
        <?php endforeach; ?>
        <?php unset($_SESSION['messages']) ?>
    <?php endif; ?>
    <h1>profile</h1>
    <section class="profile">
        <?php if (!isset($_SESSION['user']['image_url'])) : ?>
            <img src="/assets/images/no-image.png" alt="no profile picture selected">
        <?php else : ?>
            <img src="<?= $_SESSION['user']['image_url'] ?>" alt="profile picture">
        <?php endif; ?>
        <p><?= $_SESSION['user']['user_name'] ?></p>
        <p><?= $_SESSION['user']['bio'] ?></p>
        <a class="button" href="/edit.php?edit=profile">edit profile</a>
        <a class="button" href="/app/users/logout.php">logout</a>
        <?php if (isset($userPosts)) : ?>
            <h2>posts</h2>
            <a class="button" href="submit.php">submit post</a>
            <ul class="user-posts">
                <?php foreach ($userPosts as $userPost) : ?>
                    <li>
                        <a href="/view.php?view=post&post_id=<?= $userPost['id'] ?>"><?= $userPost['title'] ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <h2>no posts</h2>
            <a class="button" href="submit.php">submit post</a>
        <?php endif; ?>
        <?php if (isset($userComments)) : ?>
            <h2>comments</h2>
            <?php foreach ($userComments as $userComment) : ?>
                <div class="comment">
                    <p>on
                        <a href="/view.php?view=post&post_id=<?= $userComment['post_id'] ?>">
                            <?= fetchPostTitle((int)$userComment['post_id'], $db) ?>
                        </a>
                    </p>
                    <p><?= $userComment['comment'] ?></p>
                    <a class="button" href="/edit.php?edit=comment&comment_id=<?= $userComment['id'] ?>">edit comment</a>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <h2>no comments</h2>
        <?php endif; ?>
    </section>
</main>
<?php
require __DIR__ . '/views/footer.php';
?>