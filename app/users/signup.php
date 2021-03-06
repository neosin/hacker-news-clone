<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (isset($_POST['username'], $_POST['signup-email'], $_POST['signup-password'], $_POST['password-check'])) {
    $newUser = [
        "user_name" => trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING)),
        "email" => trim(filter_var($_POST['signup-email'], FILTER_SANITIZE_EMAIL)),
        "password" => $_POST['signup-password'],
        "password-check" => $_POST['password-check'],
    ];

    $_SESSION['return'] = [
        "user_name" => $newUser['user_name'],
        "email" => $newUser['email'],
    ];

    if (emptyInput($newUser)) {
        addMessage('Empty fields');
        header("Location: /../../signup.php");
        exit;
    }

    if (userNameExists($newUser['user_name'], $db)) {
        addMessage('Username already registered');
        unset($_SESSION['return']['user_name']);
        header("Location: /../../signup.php");
        exit;
    }

    if (!validEmail($newUser['email'])) {
        addMessage('Invalid email');
        unset($_SESSION['return']['email']);
        header("Location: /../../signup.php");
        exit;
    }

    if (userEmailExists($newUser['email'], $db)) {
        addMessage('Email already registered');
        unset($_SESSION['return']['email']);
        header("Location: /../../signup.php");
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
    addMessage('Welcome ' . $newUser['user_name'] . "!");
    unset($_SESSION['return']);
}

header("Location: /");
