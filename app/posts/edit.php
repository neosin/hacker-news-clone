<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

$messages = [];

if (userLoggedIn() && isset($_POST['post_id'])) { //current title/desc/url?
    $userId = (int)filter_var($_SESSION['user']['id'], FILTER_SANITIZE_NUMBER_INT);
    $postId = (int)filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);

    if (isset($_POST['title'])) {
        //validate title?
        $newTitle = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        editPostTitle($userId, $postId, $newTitle, $db);
        $messages[] = "Title changed";
    }

    if (isset($_POST['description'])) {
        $newDescription = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        editPostDescription($userId, $postId, $newDescription, $db);
        $messages[] = "Description changed";
    }

    if (isset($_POST['url'])) {
        $newUrl = filter_var($_POST['url'], FILTER_SANITIZE_URL);
        if (!validUrl($newUrl)) {
            $newUrl = NULL;
            $messages[] = "Invalid URL";
        } else {
            editPostUrl($userId, $postId, $newUrl, $db);
            $messages[] = "URL changed";
        }
    }

    if (isset($_POST['delete'])) {
        deletePost($userId, $postId, $db);
        $_SESSION['messages'] = "Post deleted";
        unset($postId); //?
    }

    if (isset($messages)) { //implement this everywhere
        $_SESSION['messages'] = $messages;
    }
} else {
    header("location: /../../profile.php");
    exit;
}

if (isset($postId)) {
    header("location: /../../edit.php?edit=post&post_id=$postId");
} else {
    header("location: /../../profile.php");
}
