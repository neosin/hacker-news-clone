<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (isset($_POST['post_id'])) {
    $postID = filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
    $post = fetchPost((int)$postID, $db);
    $response = $post;
} else {
    $response = "Nothing here";
}

header('Content-Type: application/json');

echo json_encode($response);
