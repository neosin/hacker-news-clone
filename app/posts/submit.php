<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (isset($_SESSION['user'])) {
    $user = [
        'id' => (int)filter_var($_SESSION['user']['id'], FILTER_SANITIZE_NUMBER_INT),
    ];

    if (isset($_POST['title'], $_POST['description'], $_POST['url'])) {
        $newPost = [
            'title' => filter_var($_POST['title'], FILTER_SANITIZE_STRING),
            'description' => filter_var($_POST['description'], FILTER_SANITIZE_STRING),
            'url' => filter_var($_POST['url'], FILTER_SANITIZE_STRING),
        ];

        if (emptyInput($newPost)) {
            $_SESSION['message'] = 'Empty fields';
            header("location: /../../submit.php");
            exit;
        }

        if (!validUrl($newPost['url'])) {
            $_SESSION['message'] = 'Invalid URL';
            header("location: /../../submit.php");
            exit;
        }

        createPost($user['id'], $newPost, $db);
    }
}

$_SESSION['message'] = 'Post submitted';
header('location: /../../submit.php');
