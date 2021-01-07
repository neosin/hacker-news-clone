<nav>
    <ul>
        <li><a href="/../index.php">home</a></li>
        <li><a href="index.php?order_by=top">top</a></li>
        <li><a href="index.php?order_by=new">new</a></li>
        <?php if (isset($_SESSION['user'])) : ?>
            <li><a href="profile.php"><?= $_SESSION['user']['user_name'] ?></a></li>
        <?php else : ?>
            <li><a href="login.php">login</a></li>
        <?php endif; ?>
    </ul>
</nav>