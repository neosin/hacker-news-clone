<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (!userLoggedIn()) {
    header('location: /');
    exit;
} else {
    if (!isset($_GET['edit'])) {
        $_GET['edit'] = 'profile';
    } else {
        if (isset($_GET['post_id'])) {
            $postId = (int)filter_var($_GET['post_id'], FILTER_SANITIZE_NUMBER_INT);
            $post = fetchPost($postId, $db);
            if (!checkUserId((int)$post['user_id'], (int)$_SESSION['user']['id'])) {
                addMessage('Post not submitted by you');
                header('location: /profile.php');
                exit;
            }
        }
        if ($_GET['edit'] === 'comment' && isset($_GET['comment_id'])) {
            $commentId = (int)filter_var($_GET['comment_id'], FILTER_SANITIZE_NUMBER_INT);
            $comment = fetchCommentForEdit($commentId, (int)$_SESSION['user']['id'], $db);
            if (!checkUserId((int)$comment['user_id'], (int)$_SESSION['user']['id'])) {
                addMessage('Comment not submited by you');
                header('location: /profile.php');
                exit;
            }
        } elseif ($_GET['edit'] === 'reply' && isset($_GET['comment_id'])) {
            $commentId = (int)filter_var($_GET['comment_id'], FILTER_SANITIZE_NUMBER_INT);
            $comment = fetchComment($commentId, $db);
        }
    }
}
?>

<main>
    <?php if ($_GET['edit'] === 'profile') : ?>
        <section class="profile">
            <?php if (!isset($_SESSION['user']['image_url'])) : ?>
                <img src="/assets/images/no-image.png" alt="no profile picture selected">
            <?php else : ?>
                <img src="<?= $_SESSION['user']['image_url'] ?>" alt="profile picture">
            <?php endif; ?>
            <a href="/edit.php?edit=profile_picture" class="button">change profile picture</a>
            <form action="/app/users/profile.php" method="post">
                <label for="user_name">user name</label>
                <input type="text" name="user_name" id="user_name" value="<?= $_SESSION['user']['user_name']; ?>" required>
                <label for="bio">bio</label>
                <textarea id="bio" name="bio" rows="4" required><?= $_SESSION['user']['bio']; ?></textarea>
                <button type="submit">confirm changes</button>
            </form>
            <div class="button-group">
                <a class="button" href="/edit.php?edit=password">change password</a>
                <a class="button" href="/edit.php?edit=email">change email</a>
            </div>
            <a class="button delete" href="/edit.php?edit=delete_profile">delete profile</a>
        </section>
    <?php elseif ($_GET['edit'] === 'profile_picture') : ?>
        <section class="profile">
            <form action="/app/users/profile.php" method="post" enctype="multipart/form-data">
                <label for="profile_picture">profile picture</label>
                <!-- migth be shorthanded, and this migth be in need of a rework -->
                <?php if (!isset($_SESSION['user']['image_url'])) : ?>
                    <img src="/assets/images/no-image.png" alt="no profile picture selected">
                <?php else : ?>
                    <img src="<?= $_SESSION['user']['image_url'] ?>" alt="profile picture">
                <?php endif; ?>
                <input type="file" name="profile_picture" id="profile_picture" accept=".jpeg, .gif, .png" required>
                <button type="submit">upload</button>
            </form>
            <?php if (isset($_SESSION['user']['image_url'])) : ?>
                <form action="/app/users/profile.php" method="post">
                    <input type="hidden" name="delete_picture" id="delete_picture" value="true">
                    <button class="delete" type="submit">delete profile picture</button>
                </form>
            <?php endif; ?>
        </section>
    <?php elseif ($_GET['edit'] === 'password') : ?>
        <form action="/app/users/profile.php" method="post">
            <label for="current_password">current password</label>
            <input type="password" name="current_password" id="current_password" required>
            <label for="new_password">new password</label>
            <input type="password" name="new_password" id="new_password" required>
            <label for="password_check">repeat new password</label>
            <input type="password" name="password_check" id="password_check" required>
            <button type="submit">submit</button>
        </form>
    <?php elseif ($_GET['edit'] === 'email') : ?>
        <form action="/app/users/profile.php" method="post">
            <label for="email">new email</label>
            <input type="email" name="email" id="email" value="<?= $_SESSION['user']['email'] ?>" required>
            <label for="password">password</label>
            <input type="password" name="password" id="password" required>
            <button class="warning" type="submit">submit</button>
        </form>
    <?php elseif ($_GET['edit'] === 'post') : ?>
        <form action="/app/posts/edit.php" method="post">
            <input type="hidden" name="post_id" id="post_id" value="<?= $post['id'] ?>">
            <label for="title">title</label>
            <input type="text" name="title" id="title" value="<?= $post['title'] ?>" required>
            <label for="description">description</label>
            <textarea id="description" name="description" rows="4" required><?= $post['description'] ?></textarea>
            <label for="url">url</label>
            <input type="url" name="url" id="url" value="<?= $post['url'] ?>" required>
            <button type="submit">submit</button>
        </form>
        <form action="/app/posts/edit.php" method="post">
            <input type="hidden" name="post_id" id="post_id" value="<?= $post['id'] ?>">
            <input type="hidden" name="delete" id="delete" value="true">
            <button class="delete" type="submit">delete post</button>
        </form>
    <?php elseif ($_GET['edit'] === 'comment') : ?>
        <form action="/app/posts/comment.php" method="post">
            <input type="hidden" name="comment_id" id="comment_id" value="<?= $comment['id'] ?>">
            <input type="hidden" name="post_id" id="post_id" value="<?= $comment['post_id'] ?>">
            <label for="edited_comment">edit comment</label>
            <textarea id="edited_comment" name="edited_comment" rows="4" required><?= $comment['comment'] ?></textarea>
            <button type="submit">submit</button>
        </form>
        <form action="/app/posts/comment.php" method="post">
            <input type="hidden" name="comment_id" id="comment_id" value="<?= $comment['id'] ?>">
            <input type="hidden" name="post_id" id="post_id" value="<?= $comment['post_id'] ?>">
            <input type="hidden" name="delete" id="delete" value="true">
            <button class="delete">delete comment</button>
        </form>
    <?php elseif ($_GET['edit'] === 'reply') : ?>
        <div class="comment">
            <a href="/view.php?view=profile&user_id=<?= $comment['user_id'] ?>"><?= fetchPoster((int)$comment['user_id'], $db) ?></a>
            <p><?= $comment['comment'] ?></p>
        </div>
        <form action="/app/posts/comment.php" method="post">
            <input type="hidden" name="comment_id" id="comment_id" value="<?= $comment['id'] ?>">
            <input type="hidden" name="post_id" id="post_id" value="<?= $comment['post_id'] ?>">
            <label for="edited_comment">reply</label>
            <textarea id="reply" name="reply" rows="4" required></textarea>
            <button type="submit">submit</button>
        </form>
    <?php elseif ($_GET['edit'] === 'delete_profile') : ?>
        <section class="delete-profile">
            <p>Warning, if you choose to delete your profile there's no going back!</p>
            <form action="/app/users/profile.php" method="post">
                <label for="password">password</label>
                <input type="password" name="password" id="password" required>
                <label for="password_check">repeat password</label>
                <input type="password" name="password_check" id="password_check" required>
                <input type="hidden" name="delete_profile" id="delete_profile" value="true">
                <button class="delete" type="submit">delete profile</button>
            </form>
        </section>
    <?php else : ?>
        <p class="message">i don't even know</p>
    <?php endif; ?>
    <?php if (isset($_SESSION['messages'])) : ?>
        <?php foreach ($_SESSION['messages'] as $message) : ?>
            <p class="message"><?= $message ?></p>
        <?php endforeach; ?>
        <?php unset($_SESSION['messages']); ?>
    <?php endif; ?>
</main>

<?php
require __DIR__ . '/views/footer.php';
?>