<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (isset($_POST['username'], $_POST['signup-email'], $_POST['signup-password'], $_POST['password-check'])) {

    $newUser = ["user_name" => $_POST['username'], "email" => $_POST['signup-email'], "password" => $_POST['signup-password'], "password-check" => $_POST['password-check']];

    if (emptyInput($newUser)) {
        $_SESSION['message'] = "Empty fields";
        header("Location: /../../signup.php");
        exit;
    }

    if (userNameExists($newUser['user_name'], $db)) {
        $_SESSION['message'] = "Username already registered";
        header("Location: /../../login.php");
        exit;
    } else {
        $newUser['user_name'] = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    }

    if (validEmail($newUser['email'])) {
        $newUser['email'] = filter_var($_POST['signup-email'], FILTER_SANITIZE_EMAIL);
    } else {
        $_SESSION['message'] = "Invalid email";
        header("Location: /../../signup.php");
        exit;
    }

    if (userEmailExists($newUser['email'], $db)) {
        $_SESSION['message'] = "Email already registered";
        header("Location: /../../login.php");
        exit;
    }

    if (!passwordMatch($newUser['password'], $newUser['password-check'])) {
        $_SESSION['message'] = "Not the same password!";
        header("Location: /../../signup.php");
        exit;
    } else {
        $newUser['password_hash'] = password_hash($newUser['password'], PASSWORD_DEFAULT);
    }

    createUser($newUser, $db);
    loginUser($newUser, $db);
    unset($newUser);
}

header("location: /../../index.php");
