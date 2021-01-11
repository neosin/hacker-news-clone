<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (userLoggedIn()) {
    header("location: /");
    exit;
}

?>
<section class="login">
    <?php if (isset($_SESSION['messages'])) : ?>
        <?php foreach ($_SESSION['messages'] as $message) : ?>
            <p class="error"><?= $message ?></p>
        <?php endforeach; ?>
        <?php unset($_SESSION['messages']) ?>
    <?php endif; ?>
    <h1>login</h1>
    <form action="/app/users/login.php" method="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Submit</button>
    </form>
    <div class="button">
        <a href="signup.php">register account</a>
    </div>
</section>

<?php
require __DIR__ . '/views/footer.php';
?>