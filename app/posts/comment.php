<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

$messages = [];

if (userLoggedIn() && isset($_POST['post_id'])) {
    $userId = (int)filter_var($_SESSION['user']['id'], FILTER_SANITIZE_NUMBER_INT);
    $postId = (int)filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);

    if (isset($_POST['comment_id'])) {
        $commentId = (int)filter_var($_POST['comment_id'], FILTER_SANITIZE_NUMBER_INT);
    }

    if (isset($_POST['comment'])) {
        $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
        addComment($userId, $postId, $comment, $db);
    }

    if (isset($_POST['edited_comment'])) {
        $editedComment = filter_var($_POST['edited_comment'], FILTER_SANITIZE_STRING);
        editComment($userId, $commentId, $editedComment, $db);
        $_SESSION['messages'][] = "Comment edited";
        header("location: /../../edit.php?edit=comment&comment_id=$commentId");
        exit;
    }

    if (isset($_POST['delete'])) {
        deleteComment($userId, $commentId, $db);
        $_SESSION['messages'][] = "Comment deleted";
    }
}

if (isset($postId)) {
    header("location: /../../view-post.php?post_id=$postId");
} else {
    header("location: /../../profile.php");
}
