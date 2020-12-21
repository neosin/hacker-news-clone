<?php

declare(strict_types=1);
require __DIR__ . '/../autoload.php';

if (isset($_POST['email'], $_POST['password'])) {

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $stmnt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmnt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $user = $stmnt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // No user
        $_SESSION['message'] = "Not registered";
        header("Location: /../../login.php");
        exit;
    }

    if (password_verify($_POST['password'], $user['password'])) {
        unset($user['password']);
        $_SESSION['user'] = $user;
    } else {
        // Wrong password
        $_SESSION['message'] = "Wrong password";
        header("Location: /../../login.php");
        exit;
    }
}
header("Location: /");
