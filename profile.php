<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (!isset($_SESSION['user'])) {
    header("location: /");
}

?>

<main>
    <h1>hello profile</h1>
    <?php if (isset($_SESSION['user'])) : ?>
        <p>Hello <?= $_SESSION['user']['email'] ?></p>
    <?php endif; ?>
    <a href="/app/users/logout.php">logout</a>
</main>

<?php
require __DIR__ . '/views/footer.php';
?>