<?php

declare(strict_types=1);
require __DIR__ . '/../autoload.php';

if (isset($_SESSION['user'])) {

    $user = [
        'id' => (int)filter_var($_SESSION['user']['id'], FILTER_SANITIZE_NUMBER_INT),
    ];

    if (isset($_FILES['profile_picture'])) { // Why does this trigger when no file is uploaded? 

        switch ($_FILES['profile_picture']['type']) {
            case 'image/gif':
                break;
            case 'image/jpeg':
                break;
            case 'image/png':
                break;
            default:
                $_SESSION['message'] = "Unsupported format";
                header("location: /../../profile.php?edit-profile=profile");
                exit;
                break;
        }

        if ($_FILES['profile_picture']['size'] >= 2000000) {
            $_SESSION['message'] = "File is to big, needs to be smaller than 2mb";
            header("location: /../../profile.php?edit-profile=profile");
            exit;
        }

        $dest = __DIR__ . '/uploads/' . $_SESSION['user']['user_name'] . '-profile-picture-' . $_FILES['profile_picture']['name'];
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $dest);
        editProfilePicture($user['id'], $dest, $db);
    }

    if (isset($_POST['user_name']) && $_POST['user_name'] !== $_SESSION['user']['user_name']) {
        $user['new_user_name'] = filter_var($_POST['user_name'], FILTER_SANITIZE_STRING);

        if (emptyInput($user)) {
            $_SESSION['message'] = "Empty fields";
            header("location: /../../profile.php?edit-profile=profile");
            exit;
        }

        if (userNameExists($user['new_user_name'], $db)) {
            $_SESSION['message'] = "Username taken";
            header("location: /../../profile.php?edit-profile=profile");
            exit;
        }

        editUserName($user['id'], $user['new_user_name'], $db);
    }

    if (isset($_POST['bio'])) {
        $user['bio'] = filter_var($_POST['bio'], FILTER_SANITIZE_STRING);
        editBio($user['id'], $user['bio'], $db);
    }

    if (isset($_POST['email'], $_POST['password'])) {

        $user['new_email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $user['password'] = $_POST['password'];

        if (emptyInput($user)) {
            $_SESSION['message'] = "Empty fields";
            header("location: /../../profile.php?edit-profile=profile");
            exit;
        }

        if (!checkPassword($user['id'], $_POST['password'], $db)) {
            header("location: /../../profile.php?edit-profile=email");
            exit;
        }

        if (!validEmail($user['new_email'])) {
            $_SESSION['message'] = "Invalid email-adress";
            header("location: /../../profile.php?edit-profile=email");
            exit;
        }

        if (userEmailExists($user['new_email'], $db)) {
            $_SESSION['message'] = "Email-adress already registered";
            header("location: /../../profile.php?edit-profile=email");
            exit;
        }

        editEmail($user['id'], $user['new_email'], $db);
        $_SESSION['message'] = "Email changed to " . $user['new_email'];
        header("location: /../../profile.php?edit-profile=email");
        exit;
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

    unset($user);
}

header("location: /../../profile.php");
