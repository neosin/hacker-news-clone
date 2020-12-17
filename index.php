<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';
?>

<main>
    <h1>hello index</h1>
    <?php if (isset($_SESSION['user'])) : ?>
        <p>Hello <?= $_SESSION['user']['email'] ?></p>
    <?php endif; ?>
</main>

<?php
require __DIR__ . '/views/footer.php';
?>