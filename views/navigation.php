<nav>
    <ul>
        <li><a href="/../index.php">home</a></li>
        <?php if (isset($_SESSION['user'])) : ?>
            <li><a href="profile.php"><?= $_SESSION['user']['user_name'] ?></a></li>
        <?php else : ?>
            <li><a href="login.php">login</a></li>
        <?php endif; ?>
    </ul>
</nav>