<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

if (userLoggedIn()) {
    header("location: /");
    exit;
}

if (isset($_SESSION['return'])) {
    $return = $_SESSION['return'];
    unset($_SESSION['return']);
}

?>

<section class="login">
    <?php if (isset($_GET['login'])) : ?>
        <?php if ($_GET['login'] === 'recover_password' || $_GET['login'] === 'process_expired') : ?>
            <h1>password assistance</h1>

            <form action="app/users/reset-password-request.php" method="post">
                <div class="form-group">
                    <label for="email"></label>
                    <input class="form-control" type="email" name="email" id="email" required>
                </div>
                <p></p>
                <button type="submit" class="btn btn-primary" id="log-in-btn">submit email</button>
            </form>
            <?= $_SESSION['messages']; ?>

        <?php elseif ($_GET['login'] === 'check_mail') : ?>
            <h1>Email sent!</h1>
            <p>use the link sent to your email to proceed</p>

        <?php elseif ($_GET['login'] === 'reset_password') : ?>
            <?php if (isset($_GET['selector'], $_GET['token'])) : ?>
                <?php $selector = filter_var($_GET['selector'], FILTER_SANITIZE_STRING); ?>
                <?php $token = filter_var($_GET['token'], FILTER_SANITIZE_STRING); ?>
                <?php if (ctype_xdigit($selector) !== false && ctype_xdigit($token) !== false) : ?>
                    <form action="app/users/reset-password.php" method="post">
                        <input type="hidden" name="selector" value="<?= $selector; ?>">
                        <input type="hidden" name="token" value="<?= $token; ?>">
                        <label for="new-password">Enter new password</label>
                        <input type="password" name="new-password" id="new-password" required>
                        <label for="confirm-password">Confirm password</label>
                        <input type="password" name="confirm-password" id="confirm-password" required>
                        <button type="submit">submit</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    <?php else : ?>
        <h1>login</h1>
        <form action="/app/users/login.php" method="post">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= isset($return) ? $return['email'] : "" ?>" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">login</button>
        </form>
        <a class="button" href="signup.php">register account</a>
        <a class="button" href="login.php?login=recover_password">forgotten password</a>


        <?php if (isset($_SESSION['messages'])) : ?>
            <?php foreach ($_SESSION['messages'] as $message) : ?>
                <p class="message"><?= $message ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['messages']) ?>
        <?php endif; ?>


    <?php endif; ?>

</section>


<?php
require __DIR__ . '/views/footer.php';
?>