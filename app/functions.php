<?php

declare(strict_types=1);

// login functions

function fetchUserData(array $user, object $db): void
{
    $id = $user['id'];
    $stmnt = $db->prepare("SELECT id, user_name, email, bio, image_url FROM users WHERE id = :id");
    $stmnt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $_SESSION['user'] = $stmnt->fetch(PDO::FETCH_ASSOC);
}

function loginUser(array $user, object $db): bool // is this the way for error-messages?
{
    $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
    $stmnt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmnt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        $_SESSION['message'] = "Not registred";
        return false;
    }

    if (password_verify($user['password'], $result['password'])) {
        unset($user['password'], $result['password']);
        $_SESSION['user'] = $result;
        return true;
    } else {
        $_SESSION['message'] = "Wrong password";
        return false;
    }
}

// signup functions

function emptyInput(array $user): bool // both array and strings ?
{

    foreach ($user as $userProperty) {
        if (empty($userProperty)) {
            return true;
        }
    }
    return false;
}

function passwordMatch(string $password, string $passwordMatch): bool
{
    if ($password !== $passwordMatch) {
        // $_SESSION['message'] = "Unmatching passwords";
        return false;
    }
    return true;
}

function validEmail(string $email): bool
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function userEmailExists(string $email, object $db): bool
{
    $stmnt = $db->prepare("SELECT email FROM users WHERE email = :email");
    $stmnt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

function userNameExists(string $userName, object $db): bool
{
    $stmnt = $db->prepare("SELECT user_name FROM users WHERE user_name = :user_name");
    $stmnt->bindParam(":user_name", $userName, PDO::PARAM_STR);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

function createUser(array $newUser, object $db): void
{
    $stmnt = $db->prepare("INSERT INTO users (user_name, email, password) VALUES (:user_name, :email, :password)");
    $stmnt->bindParam(":user_name", $newUser['user_name'], PDO::PARAM_STR);
    $stmnt->bindParam(":email", $newUser['email'], PDO::PARAM_STR);
    $stmnt->bindParam(":password", $newUser['password_hash'], PDO::PARAM_STR);
    $stmnt->execute();
    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

// validUserName ?

// edit profile functions

function editUserName(int $id, string $userName, object $db): void
{
    $stmnt = $db->prepare("UPDATE users SET user_name = :user_name WHERE id = :id");
    $stmnt->bindParam(":user_name", $userName, PDO::PARAM_STR);
    $stmnt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

function editBio(int $id, string $bio, object $db): void
{
    $stmnt = $db->prepare("UPDATE users SET bio = :bio WHERE id = :id");
    $stmnt->bindParam(":bio", $bio, PDO::PARAM_STR);
    $stmnt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

// editEmail

function editEmail(int $id, string $newEmail, object $db): void
{
    $stmnt = $db->prepare("UPDATE users SET email = :email WHERE id = :id");
    $stmnt->bindParam(":email", $newEmail, PDO::PARAM_STR);
    $stmnt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

// editProfilePicture
function editProfilePicture(int $id, string $imageURL, object $db): void
{
    $imageURL = explode("/app", $imageURL);
    $imageURL = '/app' . $imageURL[1];

    $stmnt = $db->prepare("UPDATE users SET image_url = :image_url WHERE id = :id");
    $stmnt->bindParam(":image_url", $imageURL, PDO::PARAM_STR);
    $stmnt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

function checkPassword(int $id, string $password, object $db): bool
{
    $stmnt = $db->prepare("SELECT password FROM users WHERE id = :id");
    $stmnt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $userPassword = $stmnt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($password, $userPassword['password'])) {
        $_SESSION['message'] = "Incorrect password";
        return false;
    }

    return true;
}

// changePassword
function changePassword(int $id, string $newPassword, object $db): void
{
    $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmnt = $db->prepare("UPDATE users SET password = :password WHERE id = :id");
    $stmnt->bindParam(":password", $newPassword, PDO::PARAM_STR);
    $stmnt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

// post functions

// createPost

// deletePost

// addComment

// editComment

// deleteComment

// checkUpvote
function checkUpvote(int $userId, int $postId, object $db): bool
{
    $stmnt = $db->prepare("SELECT * FROM upvotes WHERE user_id = :user_id AND post_id = :post_id;");
    $stmnt->bindParam(":user_id", $userId, PDO::PARAM_INT);
    $stmnt->bindParam(":post_id", $postId, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        return false;
    }

    return true;
}

// addUpvote
function addUpvote(int $postId, object $db): void
{
    $post = fetchPost($postId, $db);
    $upvotes = (int)$post['upvotes'];
    $upvotes += 1;
    $stmnt = $db->prepare("UPDATE posts SET upvotes = :upvotes");
    $stmnt->bindParam(":upvotes", $upvotes, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

// fetchPost
function fetchPost(int $postId, object $db): array
{
    $stmnt = $db->prepare("SELECT * FROM posts WHERE id = :post_id");
    $stmnt->bindParam(":post_id", $postId, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    return $stmnt->fetch(PDO::FETCH_ASSOC);
}

// fetchPosts
function fetchPosts(int $offset, object $db): array
{
    $stmnt = $db->prepare("SELECT * FROM posts ORDER BY creation LIMIT 10 OFFSET :offset;");
    $stmnt->bindParam(":offset", $offset, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    return $stmnt->fetchAll(PDO::FETCH_ASSOC);
}

// fetchPoster
function fetchPoster(int $id, object $db): string
{
    $stmnt = $db->prepare("SELECT user_name FROM users WHERE id = :id;");
    $stmnt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    return $result['user_name'];
}

// fetchNumberOfPosts
function fetchNumberOfPosts($db): int
{
    $stmnt = $db->query("SELECT COUNT(id) as 'number-of-posts' FROM posts");
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    return (int)$result['number-of-posts'];
}
