<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (isset($_POST['selector'], $_POST['token'], $_POST['new-password'], $_POST['confirm-password'])) {
    $selector = filter_var($_POST['selector'], FILTER_SANITIZE_STRING);
    $token = filter_var($_POST['token'], FILTER_SANITIZE_STRING);
    $newPwd = $_POST['new-password'];
    $confirmPwd = filter_var($_POST['confirm-password'], FILTER_SANITIZE_STRING);
    $currentTime = date("U");

    $url = "/../../login.php?login=reset_password&selector=" . $selector . "&token=" . $token;
    if ($newPwd !== $confirmPwd) {
        $_SESSION['messages'] = "Password did not match.";
        exit(header("location: /../../login.php?login=reset_password&selector=" . $selector . "&token=" . $token));
    }

    $statement = $db->query("SELECT * FROM reset_passwords WHERE selector = '$selector'");
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result === null) {
        $_SESSION['messages'] = "Something went wrong! Please try again.";
        header('/recover-password.php');
    }
    if ($currentTime > $result['expires']) {
        $_SESSION['messages'] = "Reset process expired. Please try again.";
        exit(header('location: /../../login.php?login=process_expired'));
    };

    $tokenBinary = hex2bin($token);

    if (password_verify($tokenBinary, $result['token'])) {
        $email = $result['email'];
        $statement = $db->prepare('SELECT * FROM users WHERE email = :email');
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
    } else {
        $_SESSION['messages'] = "Reset process expired. Please try again.";
        header('location: /../../login.php?login=password_update_unsuccessful');
    }

    $newPwdHashed = password_hash($newPwd, PASSWORD_DEFAULT);
    $statement = $db->prepare('UPDATE users SET password = :password WHERE email = :email');
    $statement->bindParam(':password', $newPwdHashed, PDO::PARAM_STR);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();
    $statement = $db->prepare('DELETE FROM reset_passwords WHERE email = :email');
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();

    // Add message
    $_SESSION['messages'] = 'Password has now been updated!';
    header('location: /../../login.php?');
}
