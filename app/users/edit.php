<?php

declare(strict_types=1);
require __DIR__ . '/../autoload.php';

if (isset($_SESSION['user'])) {

    $user = [
        'id' => (int)filter_var($_SESSION['user']['id'], FILTER_SANITIZE_NUMBER_INT),
    ];

    if (isset($_POST['user_name']) && $_POST['user_name'] !== $_SESSION['user']['user_name']) {
        $user['new_user_name'] = filter_var($_POST['user_name'], FILTER_SANITIZE_STRING);

        if (emptyInput($user)) {
            $_SESSION['message'] = "Empty fields";
            header("location: /../../profile.php?edit-profile=1");
            exit; // ? 
        }

        if (userNameExists($user['new_user_name'], $db)) {
            $_SESSION['message'] = "Username taken";
            header("location: /../../profile.php?edit-profile=1");
            exit; // ? 
        }

        editUserName($user['id'], $user['new_user_name'], $db);
    }

    if (isset($_POST['bio'])) {
        $user['bio'] = filter_var($_POST['bio'], FILTER_SANITIZE_STRING);
        editBio($user['id'], $user['bio'], $db);
    }
}


header("location: /../../profile.php");
