<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (userLoggedIn()) {
    header("location: /");
    exit;
}

?>

<section>
    <h1>login</h1>
    <form action="/app/users/login.php" method="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Submit</button>
    </form>
    <?php if (isset($_SESSION['messages'])) : ?>
        <?php foreach ($_SESSION['messages'] as $message) : ?>
            <p><?= $message ?></p>
        <?php endforeach; ?>
        <?php unset($_SESSION['messages']) ?>
    <?php endif; ?>
</section>

<a href="signup.php">register account</a>

<?php
require __DIR__ . '/views/footer.php';
?>