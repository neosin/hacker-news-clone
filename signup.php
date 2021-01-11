<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (userLoggedIn()) {
    header("location: /");
    exit;
}

?>
<main>
    <section>
        <?php if (isset($_SESSION['messages'])) : ?>
            <?php foreach ($_SESSION['messages'] as $message) : ?>
                <p><?= $message ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['messages']) ?>
        <?php endif; ?>
        <h1>signup</h1>
        <form action="/app/users/signup.php" method="post">
            <label for="username">username</label>
            <input type="text" name="username" id="username" required>
            <label for="signup-email">email</label>
            <input type="mail" name="signup-email" id="signup-email" required>
            <label for="signup-password">password</label>
            <input type="password" name="signup-password" id="signup-password" required>
            <label for="password-check">vertify password</label>
            <input type="password" name="password-check" id="password-check" required>
            <button type="submit">submit</button>
        </form>
    </section>
</main>

<?php
require __DIR__ . '/views/footer.php';
?>