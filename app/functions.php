<?php

declare(strict_types=1);

// general functions

function userLoggedIn(): bool
{
    if (isset($_SESSION['user'])) {
        return true;
    }

    return false;
}

// login functions

function setUserData(array $user, object $db): void
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

function loginUser(array $user, object $db): bool
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
        $_SESSION['messages'] = "Not registred";
        return false;
    }

    if (password_verify($user['password'], $result['password'])) {
        unset($user['password'], $result['password']);
        $_SESSION['user'] = $result;
        return true;
    } else {
        $_SESSION['messages'] = "Wrong password";
        return false;
    }
}

// signup functions

function emptyInput(array $input): bool
{

    foreach ($input as $inputProperty) {
        if (empty($inputProperty)) {
            return true;
        }
    }
    return false;
}

function passwordMatch(string $password, string $passwordMatch): bool
{
    if ($password !== $passwordMatch) {
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
        $_SESSION['messages'] = "Incorrect password";
        return false;
    }

    return true;
}

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

function validUrl(string $url): bool
{
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return true;
    }

    return false;
}

function createPost(int $id, array $newPost, object $db): void
{
    $time = date('Y-m-d H:i:s');
    $stmnt = $db->prepare("INSERT INTO posts (user_id, title, description, url, creation_time) VALUES (:user_id, :title, :description, :url, :creation_time)");
    $stmnt->bindParam(":user_id", $id, PDO::PARAM_INT);
    $stmnt->bindParam(":title", $newPost['title'], PDO::PARAM_STR);
    $stmnt->bindParam(":description", $newPost['description'], PDO::PARAM_STR);
    $stmnt->bindParam(":url", $newPost['url'], PDO::PARAM_STR);
    $stmnt->bindParam("creation_time", $time, PDO::PARAM_STR);

    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

function fetchUserPosts(int $id, object $db): array
{
    $stmnt = $db->prepare("SELECT * FROM posts WHERE user_id = :user_id");
    $stmnt->bindParam("user_id", $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    return $stmnt->fetchAll(PDO::FETCH_ASSOC);
}

function editPostTitle(int $userId, int $postId, string $newTitle, object $db): void
{
    $stmnt = $db->prepare("UPDATE posts SET title = :title WHERE id = :post_id AND user_id = :user_id");
    $stmnt->bindParam(":user_id", $userId, PDO::PARAM_STR);
    $stmnt->bindParam(":post_id", $postId, PDO::PARAM_STR);
    $stmnt->bindParam(":title", $newTitle, PDO::PARAM_STR);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

function editPostDescription(int $userId, int $postId, string $newDescription, object $db): void
{
    $stmnt = $db->prepare("UPDATE posts SET description = :description WHERE id = :post_id AND user_id = :user_id");
    $stmnt->bindParam(":user_id", $userId, PDO::PARAM_STR);
    $stmnt->bindParam(":post_id", $postId, PDO::PARAM_STR);
    $stmnt->bindParam(":description", $newDescription, PDO::PARAM_STR);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

function editPostUrl(int $userId, int $postId, string $newUrl, object $db): void
{
    $stmnt = $db->prepare("UPDATE posts SET url = :url WHERE id = :post_id AND user_id = :user_id");
    $stmnt->bindParam(":user_id", $userId, PDO::PARAM_STR);
    $stmnt->bindParam(":post_id", $postId, PDO::PARAM_STR);
    $stmnt->bindParam(":url", $newUrl, PDO::PARAM_STR);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

function deletePost(int $userId, int $postId, object $db): void //bool?
{
    $stmnt = $db->prepare("DELETE FROM posts WHERE id = :post_id AND user_id = :user_id");
    $stmnt->bindParam(":post_id", $postId, PDO::PARAM_STR);
    $stmnt->bindParam(":user_id", $userId, PDO::PARAM_STR);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

// addComment
// editComment
// deleteComment

// print posts
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

function fetchPosts(int $page, object $db, bool $orderByUpvotes = false): array
{
    $offset = 0;
    $sql = "";
    $numberOfPosts = fetchTotalNumberOfPosts($db);

    for ($i = 0; $i < $page; $i++) {
        $offset += 10;
        if ($offset >= $numberOfPosts) {
            $offset = $numberOfPosts - 10;
        }
    }

    if ($orderByUpvotes) {
        $sql = "SELECT p.*, COALESCE(v.upvote_count, 0) AS upvotes
        FROM posts p
        LEFT OUTER JOIN (
            SELECT post_id, COUNT(post_id) AS upvote_count
            FROM upvotes
            GROUP BY post_id
        ) v
        ON p.id = v.post_id
        ORDER BY upvotes DESC, p.creation_time DESC
        LIMIT 10 OFFSET :offset;";
    } else {
        $sql = "SELECT p.*, COALESCE(v.upvote_count, 0) AS upvotes
        FROM posts p
        LEFT OUTER JOIN (
            SELECT post_id, COUNT(post_id) AS upvote_count
            FROM upvotes
            GROUP BY post_id
        ) v
        ON p.id = v.post_id
        ORDER BY p.creation_time DESC
        LIMIT 10 OFFSET :offset;";
    }

    $stmnt = $db->prepare($sql);
    $stmnt->bindParam(":offset", $offset, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function fetchTotalNumberOfPosts($db): int
{
    $stmnt = $db->query("SELECT COUNT(id) as 'number-of-posts' FROM posts");
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    return (int)$result['number-of-posts'];
}

// votes

function fetchUpvotes(int $postId, object $db): int
{
    $stmnt = $db->prepare("SELECT COUNT(post_id) as 'upvotes' FROM upvotes WHERE post_id = :post_id;");
    $stmnt->bindParam(":post_id", $postId, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    return (int)$result['upvotes'];
}

function userUpvote(int $userId, int $postId, object $db): bool
{
    $stmnt = $db->prepare("SELECT * FROM upvotes WHERE user_id = :user_id AND post_id = :post_id");
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

function toggleUpvote(int $userId, int $postId, object $db): void
{
    if (userUpvote($userId, $postId, $db)) {
        $stmnt = $db->prepare("DELETE FROM upvotes WHERE user_id = :user_id AND post_id = :post_id");
        $stmnt->bindParam(":user_id", $userId, PDO::PARAM_INT);
        $stmnt->bindParam(":post_id", $postId, PDO::PARAM_INT);
        $stmnt->execute();

        if (!$stmnt) {
            die(var_dump($db->errorInfo()));
        }
    } else {
        $stmnt = $db->prepare("INSERT INTO upvotes (user_id, post_id) VALUES(:user_id, :post_id)");
        $stmnt->bindParam(":user_id", $userId, PDO::PARAM_INT);
        $stmnt->bindParam(":post_id", $postId, PDO::PARAM_INT);
        $stmnt->execute();

        if (!$stmnt) {
            die(var_dump($db->errorInfo()));
        }
    }
}
