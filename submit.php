<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (!userLoggedIn()) {
    header("location: /");
    exit;
}

if (isset($_SESSION['return'])) {
    $return = $_SESSION['return'];
    var_dump($return);
    unset($_SESSION['return']);
}

?>

<main>
    <section class="submit">
        <h1>submit post</h1>
        <form action="/app/posts/submit.php" method="post">
            <label for="title">title</label>
            <input type="text" name="title" id="title" value="<?= isset($return['title']) ? $return['title'] : "" ?>" required>
            <label for="description">description</label>
            <input type="text" name="description" id="description" value="<?= isset($return['description']) ? $return['description'] : "" ?>" required>
            <label for="url">link</label>
            <input type="url" name="url" id="url" value="<?= isset($return['url']) ? $return['url'] : "" ?>" required>
            <button type="submit">submit</button>
        </form>
    </section>
    <?php if (isset($_SESSION['messages'])) : ?>
        <?php foreach ($_SESSION['messages'] as $message) : ?>
            <p class="message"><?= $message ?></p>
        <?php endforeach; ?>
        <?php unset($_SESSION['messages']) ?>
    <?php endif; ?>
</main>

<?php
require __DIR__ . '/views/footer.php';
?>