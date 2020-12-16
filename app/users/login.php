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
    } else {
        $user = $stmnt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            header("Location: /login.php");
            exit;
        }
        if (password_verify($_POST['password'], $user['password'])) {
            unset($user['password']);
            unset($_POST);
            $_SESSION['user'] = $user;
        }
    }
}

header("Location: /");
