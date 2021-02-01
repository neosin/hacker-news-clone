<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';


if (userLoggedIn()) {
    $userId = (int)filter_var($_SESSION['user']['id'], FILTER_SANITIZE_NUMBER_INT);

    if (isset($_POST['comment_id'])) {
        $commentId = (int)filter_var($_POST['comment_id'], FILTER_SANITIZE_NUMBER_INT);
        toggleCommentUpvote($userId, $commentId, $db);
        $response = fetchCommentUpvotes($commentId, $db);
    }
}

header('Content-Type: application/json');
echo json_encode($response);
