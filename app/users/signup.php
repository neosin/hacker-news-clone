<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (isset($_POST['username'],
$_POST['signup-email'],
$_POST['signup-password'],
$_POST['password-check'])) {
    $newUser = [
        "user_name" => filter_var($_POST['username'], FILTER_SANITIZE_STRING),
        "email" => filter_var($_POST['signup-email'], FILTER_SANITIZE_EMAIL),
        "password" => $_POST['signup-password'],
        "password-check" => $_POST['password-check'],
    ];

    if (emptyInput($newUser)) {
        addMessage('Empty fields');
        header("Location: /../../signup.php");
        exit;
    }

    if (userNameExists($newUser['user_name'], $db)) {
        addMessage('Username already registered');
        header("Location: /../../signup.php");
        exit;
    }

    if (!validEmail($newUser['email'])) {
        addMessage('Invalid email');
        header("Location: /../../signup.php");
        exit;
    }

    if (userEmailExists($newUser['email'], $db)) {
        addMessage('Email already registered');
        header("Location: /../../login.php");
        exit;
    }

    if (!passwordMatch($newUser['password'], $newUser['password-check'])) {
        addMessage('Unmatching passwords');
        header("Location: /../../signup.php");
        exit;
    }

    $newUser['password_hash'] = password_hash($newUser['password'], PASSWORD_DEFAULT);
    createUser($newUser, $db);
    loginUser($newUser, $db);
    unset($newUser);
}

header("location: /../../index.php");
