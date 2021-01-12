<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

$messages = [];

if (userLoggedIn()) {
    $user = [
        'id' => (int)filter_var($_SESSION['user']['id'], FILTER_SANITIZE_NUMBER_INT),
    ];

    if (isset($_FILES['profile_picture'])) {
        switch ($_FILES['profile_picture']['type']) {
            case 'image/gif':
                break;
            case 'image/jpeg':
                break;
            case 'image/png':
                break;
            case '':
                addMessage('You need to select a file to upload of the format gif, png or jpeg');
                header("location: /../../edit.php?edit=profile");
                exit;
                break;
            default:
                addMessage('Unsupported format, choose gif, png or jpeg');
                header("location: /../../edit.php?edit=profile");
                exit;
                break;
        }

        if ($_FILES['profile_picture']['size'] >= 2000000) {
            addMessage('File is to big, needs to be smaller than 2mb');
            header("location: /../../edit.php?edit=profile");
            exit;
        }

        $dest = __DIR__ . '/uploads/' . $_SESSION['user']['user_name'] . '-profile-picture-' . $_FILES['profile_picture']['name'];
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $dest);
        editProfilePicture($user['id'], $dest, $db);
    }

    if (isset($_POST['user_name']) && $_POST['user_name'] !== $_SESSION['user']['user_name']) {
        $user['new_user_name'] = trim(filter_var($_POST['user_name'], FILTER_SANITIZE_STRING));

        if (emptyInput($user)) {
            addMessage('Empty fields');
            header("location: /../../edit.php?edit=profile");
            exit;
        }

        if (userNameExists($user['new_user_name'], $db)) {
            addMessage('Username taken');
            header("location: /../../edit.php?edit=profile");
            exit;
        }

        editUserName($user['id'], $user['new_user_name'], $db);
    }

    if (isset($_POST['bio']) && $_POST['bio'] !== $_SESSION['user']['bio']) {
        $user['bio'] = trim(filter_var($_POST['bio'], FILTER_SANITIZE_STRING));
        editBio($user['id'], $user['bio'], $db);
    }

    if (isset($_POST['email'], $_POST['password'])) {
        $user['new_email'] = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
        $user['password'] = $_POST['password'];

        if (emptyInput($user)) {
            addMessage('Empty fields');
            header("location: /../../edit.php?edit=profile");
            exit;
        }

        if (!checkPassword($user['id'], $_POST['password'], $db)) {
            addMessage('Incorrect password');
            header("location: /../../edit.php?edit=email");
            exit;
        }

        if (!validEmail($user['new_email'])) {
            addMessage('Invalid email-adress');
            header("location: /../../edit.php?edit=email");
            exit;
        }

        if (userEmailExists($user['new_email'], $db)) {
            addMessage('Email-adress already registered');
            header("location: /../../edit.php?edit=email");
            exit;
        }

        editEmail($user['id'], $user['new_email'], $db);
        addMessage('Email changed to ' . $user['new_email']);
        header("location: /../../edit.php?edit=email");
        exit;
    }

    if (isset($_POST['current_password'], $_POST['new_password'], $_POST['password_check'])) {
        if (!checkPassword($user['id'], $_POST['current_password'], $db)) {
            addMessage('Incorrect password');
            header("location: /../../edit.php?edit=password");
            exit;
        }

        if (!passwordMatch($_POST['new_password'], $_POST['password_check'])) {
            addMessage('Unmatching passwords');
            header("location: /../../edit.php?edit=password");
            exit;
        }

        changePassword($user['id'], $_POST['new_password'], $db);
        addMessage('Password changed!');
        header("location: /../../edit.php?edit=password");
        exit;
    }
    unset($user);
}

header("location: /../../profile.php");
