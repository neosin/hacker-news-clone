<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (!isset($_SESSION['user'])) {
    header("location: /");
}

fetchUserData($_SESSION['user'], $db);

print_r($_SESSION['user']);

?>
<main>
    <section>
        <?php if (!isset($_GET["edit-profile"])) : ?>
            <h1>profile page</h1>
            <?php if (!isset($_SESSION['user']['image_url'])) : ?>
                <img src="/assets/images/no-image.png" alt="no profile picture selected">
            <?php else : ?>
                <img src="<?= $_SESSION['user']['image_url'] ?>" alt="profile picture">
            <?php endif; ?>
            <p><?= $_SESSION['user']['user_name'] ?></p>
            <p><?= $_SESSION['user']['bio'] ?></p>
            <a href="/profile.php?edit-profile=1">edit profile</a>
            <a href="/app/users/logout.php">logout</a>
        <?php else : ?>
            <form action="/app/users/edit.php" method="post" enctype="multipart/form-data">
                <?php if (!isset($_SESSION['user']['image_url'])) : ?>
                    <img src="/assets/images/no-image.png" alt="no profile picture selected">
                <?php else : ?>
                    <img src="<?= $_SESSION['user']['image_url'] ?>" alt="profile picture">
                <?php endif; ?>
                <label for="user_name">user name</label>
                <input type="text" name="user_name" id="user_name" value="<?= $_SESSION['user']['user_name']; ?>">
                <label for="bio">bio</label>
                <textarea id="bio" name="bio" rows="4"><?= $_SESSION['user']['bio']; ?></textarea>
                <button type="submit">submit</button>
            </form>
        <?php endif; ?>
        <?php if (isset($_SESSION['message'])) : ?>
            <p><?= $_SESSION['message'] ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    </section>
</main>


<?php
require __DIR__ . '/views/footer.php';
?>