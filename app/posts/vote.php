<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (userLoggedIn()) {
    $userId = (int)filter_var($_SESSION['user']['id'], FILTER_SANITIZE_NUMBER_INT);

    if (isset($_POST['post_id'])) {
        $postId = (int)filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
        toggleUpvote($userId, $postId, $db);
        $response = fetchUpvotes($postId, $db);
    };
}

header('Content-Type: application/json');
echo json_encode($response);
