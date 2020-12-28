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
            header("location: /../../profile.php?edit-profile=profile");
            exit; // ? 
        }

        if (userNameExists($user['new_user_name'], $db)) {
            $_SESSION['message'] = "Username taken";
            header("location: /../../profile.php?edit-profile=profile");
            exit; // ? 
        }

        editUserName($user['id'], $user['new_user_name'], $db);
    }

    if (isset($_POST['bio'])) {
        $user['bio'] = filter_var($_POST['bio'], FILTER_SANITIZE_STRING);
        editBio($user['id'], $user['bio'], $db);
    }

    if (isset($_POST['current_password'], $_POST['new_password'], $_POST['password_check'])) {

        if (!checkPassword($user['id'], $_POST['current_password'], $db)) {
            header("location: /../../profile.php?edit-profile=password");
            exit;
        }

        if (!passwordMatch($_POST['new_password'], $_POST['password_check'])) {
            header("location: /../../profile.php?edit-profile=password");
            exit;
        }

        changePassword($user['id'], $_POST['new_password'], $db);
        $_SESSION['message'] = "Password changed!";
        header("location: /../../profile.php?edit-profile=password");
        exit;
    }
}

header("location: /../../profile.php");
