<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (userLoggedIn()) {
    $user = [
        'id' => (int)filter_var($_SESSION['user']['id'], FILTER_SANITIZE_NUMBER_INT),
    ];

    if (isset($_POST['post_id'])) {
        $postID = (int)filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
        toggleUpvote($user['id'], $postID, $db);
        $response = fetchUpvotes($postID, $db);
    };
}

header('Content-Type: application/json');

echo json_encode($response);
