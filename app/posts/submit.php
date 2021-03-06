<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (userLoggedIn()) {
    $userId = (int)filter_var($_SESSION['user']['id'], FILTER_SANITIZE_NUMBER_INT);

    if (isset($_POST['title'], $_POST['description'], $_POST['url'])) {
        $newPost = [
            'title' => trim(filter_var($_POST['title'], FILTER_SANITIZE_STRING)),
            'description' => trim(filter_var($_POST['description'], FILTER_SANITIZE_STRING)),
            'url' => trim(filter_var($_POST['url'], FILTER_SANITIZE_URL)),
        ];

        if (emptyInput($newPost)) {
            addMessage('Empty fields');
            $_SESSION['return'] = $newPost;
            header("location: /../../submit.php");
            exit;
        }

        if (!validUrl($newPost['url'])) {
            addMessage('Invalid URL');
            unset($newPost['url']);
            $_SESSION['return'] = $newPost;
            header("location: /../../submit.php");
            exit;
        }

        createPost($userId, $newPost, $db);
        addMessage('Post submitted');
        $postId = fetchLatestPostId($db);
    }
}

header("location: /../../view.php?view=post&post_id=$postId");
