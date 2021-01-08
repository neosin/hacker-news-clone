<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (userLoggedIn() && isset($_POST['post_id'])) {
    $userId = (int)filter_var($_SESSION['user']['id'], FILTER_SANITIZE_NUMBER_INT);
    $postId = (int)filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
    $post = fetchPost($postId, $db);

    if (isset($_POST['title']) && $_POST['title'] !== $post['title']) { //validate title?
        $newTitle = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        editPostTitle($userId, $postId, $newTitle, $db);
        addMessage('Title changed');
    }

    if (isset($_POST['description']) && $_POST['description'] !== $post['description']) {
        $newDescription = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        editPostDescription($userId, $postId, $newDescription, $db);
        addMessage('Description changed');
    }

    if (isset($_POST['url']) && $_POST['url'] !== $post['url']) {
        $newUrl = filter_var($_POST['url'], FILTER_SANITIZE_URL);
        if (!validUrl($newUrl)) {
            addMessage('Invalid URL');
            header("location: /../../edit.php?edit=post&post_id=$postId");
            exit;
        }
        editPostUrl($userId, $postId, $newUrl, $db);
        addMessage('URL changed');
    }

    if (isset($_POST['delete'])) {
        deletePost($userId, $postId, $db);
        addMessage('Post deleted');
        unset($postId);
    }
} else {
    header("location: /../../profile.php");
    exit;
}
if (isset($postId)) {
    header("location: /../../edit.php?edit=post&post_id=$postId");
} else { //?
    header("location: /../../profile.php");
}
