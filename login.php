<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';
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
    <?php if (isset($_SESSION['message'])) : ?>
        <p><?= $_SESSION['message'] ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
</section>

<a href="signup.php">register account</a>

<?php
require __DIR__ . '/views/footer.php';
?>