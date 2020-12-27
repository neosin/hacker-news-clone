<?php

declare(strict_types=1);

// login functions

function fetchUserData(array $user, object $db): void
{
    $id = $user['id'];
    $stmnt = $db->prepare("SELECT id, user_name, email, bio, image_url FROM users WHERE id = :id");
    $stmnt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $_SESSION['user'] = $stmnt->fetch(PDO::FETCH_ASSOC);
}

function loginUser(array $user, object $db): bool
{
    $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
    $stmnt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmnt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC); // rename this to something more suiting.

    if (!$result) {
        $_SESSION['message'] = "Not registred";
        return false;
    }

    if (password_verify($user['password'], $result['password'])) {
        unset($user['password'], $result['password']);
        $_SESSION['user'] = $result;
        return true;
    } else {
        $_SESSION['message'] = "Wrong password";
        return false;
    }
}

// signup functions

function emptyInput(array $user): bool
{
    foreach ($user as $userProperty) {
        if (empty($userProperty)) {
            return true;
        }
    }
    return false;
}

function passwordCheck(string $password, string $passwordCheck): bool
{
    if ($password === $passwordCheck) {
        return true;
    }
    return false;
}

function validEmail(string $email): bool
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function userNameExists(string $userName, object $db): bool
{
    $stmnt = $db->prepare("SELECT user_name FROM users WHERE user_name = :user_name");
    $stmnt->bindParam(":user_name", $userName, PDO::PARAM_STR);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

function userEmailExists(string $email, object $db): bool
{
    $stmnt = $db->prepare("SELECT email FROM users WHERE email = :email");
    $stmnt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

function createUser(array $newUser, object $db): void
{
    $stmnt = $db->prepare("INSERT INTO users (user_name, email, password) VALUES (:user_name, :email, :password)");
    $stmnt->bindParam(":user_name", $newUser['user_name'], PDO::PARAM_STR);
    $stmnt->bindParam(":email", $newUser['email'], PDO::PARAM_STR);
    $stmnt->bindParam(":password", $newUser['password_hash'], PDO::PARAM_STR);
    $stmnt->execute();
    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

// validUserName ?

// edit profile functions

// editUsername
function editUserName(int $id, string $userName, object $db): void
{
    $stmnt = $db->prepare("UPDATE users SET user_name = :user_name WHERE id = :id");
    $stmnt->bindParam(":user_name", $userName, PDO::PARAM_STR);
    $stmnt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

// editBio
function editBio(int $id, string $bio, object $db): void
{
    $stmnt = $db->prepare("UPDATE users SET bio = :bio WHERE id = :id");
    $stmnt->bindParam(":bio", $bio, PDO::PARAM_STR);
    $stmnt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

// editProfilePicture

// changePassword
