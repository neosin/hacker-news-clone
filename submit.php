<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (!userLoggedIn()) {
    header("location: /");
    exit;
}

?>

<main>
    <section>
        <h1>submit new post</h1>
        <form action="/app/posts/submit.php" method="post">
            <label for="title">title</label>
            <input type="text" name="title" id="title" required>
            <label for="description">description</label>
            <input type="text" name="description" id="description" required>
            <label for="url">link</label>
            <input type="url" name="url" id="url" required>
            <button type="submit">submit</button>
        </form>
    </section>
    <?php if (isset($_SESSION['messages'])) : ?>
        <?php foreach ($_SESSION['messages'] as $message) : ?>
            <p><?= $message ?></p>
        <?php endforeach; ?>
        <?php unset($_SESSION['messages']) ?>
    <?php endif; ?>
</main>

<?php
require __DIR__ . '/views/footer.php';
?>