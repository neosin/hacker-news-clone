<nav>
    <ul>
        <li><a href="/../index.php">home</a></li>
        <li><a href="">test</a></li>
        <?php if (isset($_SESSION['user'])) : ?>
            <li><i>logged in as <?= $_SESSION['user']['user_name'] ?> </i><a href="app/users/logout.php">logout</a></li>
        <?php else : ?>
            <li><a href="login.php">login</a></li>
        <?php endif; ?>
    </ul>
</nav>