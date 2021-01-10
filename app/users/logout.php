<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (userLoggedIn()) {
    unset($_SESSION['user']);
    session_destroy();
}

header("Location: /");
