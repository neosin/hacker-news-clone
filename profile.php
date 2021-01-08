<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (!userLoggedIn()) {
    header("location: /");
} else {
    $userPosts = fetchUserPosts($_SESSION['user']['id'], $db);
}

?>
<main>
    <section>
        <h1>profile</h1>
        <?php if (!isset($_SESSION['user']['image_url'])) : ?>
            <img src="/assets/images/no-image.png" alt="no profile picture selected">
        <?php else : ?>
            <img src="<?= $_SESSION['user']['image_url'] ?>" alt="profile picture">
        <?php endif; ?>
        <p><?= $_SESSION['user']['user_name'] ?></p>
        <p><?= $_SESSION['user']['bio'] ?></p>
        <a href="/edit.php?edit=profile"><button>edit profile</button></a>
        <a href="/app/users/logout.php"><button>logout</button></a>
        <h2>posts</h2>
        <?php if (isset($userPosts)) : ?>
            <ul>
                <?php foreach ($userPosts as $userPost) : ?>
                    <li><a href="/edit.php?edit=post&post_id=<?= $userPost['id'] ?>"><?= $userPost['title'] ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php if (isset($_SESSION['messages'])) : ?>
            <?php foreach ($_SESSION['messages'] as $message) : ?>
                <p><?= $message ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['messages']) ?>
        <?php endif; ?>
    </section>
</main>
<?php
require __DIR__ . '/views/footer.php';
?>