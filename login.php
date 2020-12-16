<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';
?>

<section>
    <form action="/app/users/login.php" method="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="mail@mail.com" required>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Submit</button>
    </form>
</section>

<?php
require __DIR__ . '/views/footer.php';
?>