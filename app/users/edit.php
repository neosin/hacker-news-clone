<?php

declare(strict_types=1);
require __DIR__ . '/../autoload.php';

if (isset($_POST['user_name'])) {

    $newUserName = filter_var($_POST['user_name'], FILTER_SANITIZE_STRING);

    // if (userNameExists($newUserName, $db)) {
    //     $_SESSION['message'] = "Username occupied";
    // }
}

// header("location: /../../profile.php");
