<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (!isset($_SESSION['user'])) {
    header("location: /");
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
    <?php if (isset($_SESSION['message'])) : ?>
        <p><?= $_SESSION['message'] ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
</main>

<?php
require __DIR__ . '/views/footer.php';
?>