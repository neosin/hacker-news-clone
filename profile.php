<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (!isset($_SESSION['user'])) {
    header("location: /");
} else {
    fetchUserData($_SESSION['user'], $db);
    $userPosts = fetchUserPosts($_SESSION['user']['id'], $db);
}

if (isset($_GET['edit-post'])) {
    (int)$postId = filter_var($_GET['edit-post'], FILTER_SANITIZE_NUMBER_INT);
    $post = fetchPost($postId, $db);
}

?>
<main>
    <section>
        <?php if (!isset($_GET["edit-profile"])) : ?>
            <h1>profile</h1>
            <?php if (!isset($_SESSION['user']['image_url'])) : ?>
                <img src="/assets/images/no-image.png" alt="no profile picture selected">
            <?php else : ?>
                <img src="<?= $_SESSION['user']['image_url'] ?>" alt="profile picture">
            <?php endif; ?>
            <p><?= $_SESSION['user']['user_name'] ?></p>
            <p><?= $_SESSION['user']['bio'] ?></p>
            <a href="/profile.php?edit-profile=profile"><button>edit profile</button></a>
            <a href="/app/users/logout.php"><button>logout</button></a>
            <h2>posts</h2>
            <?php if (isset($userPosts)) : ?>
                <ul>
                    <?php foreach ($userPosts as $userPost) : ?>
                        <li><a href="/profile.php?edit-post=<?= $userPost['id'] ?>"><?= $userPost['title'] ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php elseif ($_GET['edit-profile'] === "profile") : ?>
            <form action="/app/users/profile.php" method="post" enctype="multipart/form-data">
                <label for="profile_picture">profile picture</label>
                <?php if (!isset($_SESSION['user']['image_url'])) : ?>
                    <img src="/assets/images/no-image.png" alt="no profile picture selected">
                <?php else : ?>
                    <img src="<?= $_SESSION['user']['image_url'] ?>" alt="profile picture">
                <?php endif; ?>
                <input type="file" name="profile_picture" id="profile_picture" accept=".jpeg, .gif, .png">
                <button type="submit">upload</button>
            </form>
            <form action="/app/users/profile.php" method="post">
                <label for="user_name">user name</label>
                <input type="text" name="user_name" id="user_name" value="<?= $_SESSION['user']['user_name']; ?>">
                <button type="submit">confirm username</button>
            </form>
            <form action="/app/users/profile.php" method="post">
                <label for="bio">bio</label>
                <textarea id="bio" name="bio" rows="4"><?= $_SESSION['user']['bio']; ?></textarea>
                <button type="submit">confirm bio</button>
            </form>
            <a href="/profile.php?edit-profile=password"><button>change password</button></a>
            <a href="/profile.php?edit-profile=email"><button>change email</button></a>
        <?php elseif ($_GET['edit-profile'] === "password") : ?>
            <form action="/app/users/profile.php" method="post">
                <label for="current_password">current password</label>
                <input type="password" name="current_password" id="current_password" required>
                <label for="new_password">new password</label>
                <input type="password" name="new_password" id="new_password" required>
                <label for="password_check">repeat new password</label>
                <input type="password" name="password_check" id="password_check" required>
                <button type="submit">submit</button>
            </form>
        <?php elseif ($_GET['edit-profile'] === 'email') : ?>
            <form action="/app/users/profile.php" method="post">
                <label for="email">edit email</label>
                <input type="email" name="email" id="email" value="<?= $_SESSION['user']['email'] ?>" required>
                <label for="password">password</label>
                <input type="password" name="password" id="password" required>
                <button type="submit">submit</button>
            </form>
        <?php endif; ?>
        <?php if (isset($_GET['edit-post'])) : ?>
            <h2><?= $post['url'] ?></h2>
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