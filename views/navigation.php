<nav>
    <ul>
        <li class="nav-item"><a href="/index.php">home</a></li>
        <li class="nav-item"><a href="/index.php?order_by=top">top</a></li>
        <li class="nav-item"><a href="/index.php?order_by=new">new</a></li>
        <?php if (userLoggedIn()) : ?>
            <li class="nav-item"><a href="/profile.php"><?= $_SESSION['user']['user_name'] ?></a></li>
        <?php else : ?>
            <li class="nav-item"><a href="/login.php">login</a></li>
        <?php endif; ?>
    </ul>
</nav>