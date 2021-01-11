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

function addMessage(string $message): void
{
    $_SESSION['messages'][] = $message;
}

function checkUserId(int $checkId, int $userId): bool
{
    if ($userId !== $checkId) {
        return false;
    }

    return true;
}

function getAge(string $birth): string
{
    $today = new DateTime();
    $birth = new DateTime($birth);
    $interval = $today->diff($birth);

    if ($interval->y === 1) {
        return $interval->y . " year ago";
    } elseif ($interval->y > 0) {
        return $interval->y . " years ago";
    }

    if ($interval->m === 1) {
        return $interval->m . " month ago";
    } else if ($interval->m > 0) {
        return $interval->m . " months ago";
    }

    if ($interval->m === 0 && $interval->days === 1) {
        return $interval->days . " day ago";
    } elseif ($interval->m === 0 && $interval->days > 0) {
        return $interval->days . " days ago";
    }

    return "today";
}

function addReturnPage(): void
{
    if (isset($_SESSION['return'])) {
        unset($_SESSION['return']);
    }

    if (isset($_SERVER['QUERY_STRING'])) {
        $_SESSION['return'] = "Location: " . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];
    } else {
        $_SESSION['return'] = "Location: " . $_SERVER['PHP_SELF'];
    }
}

// login functions

function setUserData(array $user, PDO $db): void //return array?
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

function loginUser(array $user, PDO $db): bool
{
    $messages = [];
    $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
    $stmnt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmnt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        addMessage('Not registered');
        return false;
    }

    if (password_verify($user['password'], $result['password'])) {
        unset($user['password'], $result['password']);
        $_SESSION['user'] = $result;
        return true;
    } else {
        addMessage('Incorrect password');
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
    }

    return false;
}

function userEmailExists(string $email, PDO $db): bool
{
    $stmnt = $db->prepare("SELECT email FROM users WHERE email = :email");
    $stmnt->bindParam(":email", $email, PDO::PARAM_STR);
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

function userNameExists(string $userName, PDO $db): bool
{
    $stmnt = $db->prepare("SELECT user_name FROM users WHERE user_name = :user_name");
    $stmnt->bindParam(":user_name", $userName, PDO::PARAM_STR);
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

function createUser(array $newUser, PDO $db): void
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

// profile functions

function editUserName(int $id, string $userName, PDO $db): void
{
    $stmnt = $db->prepare("UPDATE users SET user_name = :user_name WHERE id = :id");
    $stmnt->bindParam(":user_name", $userName, PDO::PARAM_STR);
    $stmnt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

function editBio(int $id, string $bio, PDO $db): void
{
    $stmnt = $db->prepare("UPDATE users SET bio = :bio WHERE id = :id");
    $stmnt->bindParam(":bio", $bio, PDO::PARAM_STR);
    $stmnt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

function editEmail(int $id, string $newEmail, PDO $db): void
{
    $stmnt = $db->prepare("UPDATE users SET email = :email WHERE id = :id");
    $stmnt->bindParam(":email", $newEmail, PDO::PARAM_STR);
    $stmnt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

function editProfilePicture(int $id, string $imageURL, PDO $db): void
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

function checkPassword(int $id, string $password, PDO $db): bool
{
    $stmnt = $db->prepare("SELECT password FROM users WHERE id = :id");
    $stmnt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $userPassword = $stmnt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($password, $userPassword['password'])) {
        return false;
    }

    return true;
}

function changePassword(int $id, string $newPassword, PDO $db): void
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

function fetchUser(int $userId, PDO $db): ?array
{
    $stmnt = $db->prepare("SELECT id, user_name, bio, image_url FROM users WHERE id = :user_id");
    $stmnt->bindParam(":user_id", $userId, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        return null;
    }

    return $result;
}

// post functions

function validUrl(string $url): bool
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }

    return true;
}

function createPost(int $id, array $newPost, PDO $db): void
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

function fetchUserPosts(int $userId, PDO $db): ?array
{
    $stmnt = $db->prepare("SELECT * FROM posts WHERE user_id = :user_id");
    $stmnt->bindParam("user_id", $userId, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $results = $stmnt->fetchAll(PDO::FETCH_ASSOC);

    if (!$results) {
        return null;
    }

    return $results;
}

function editPostTitle(int $userId, int $postId, string $newTitle, PDO $db): void
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

function editPostDescription(int $userId, int $postId, string $newDescription, PDO $db): void
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

function editPostUrl(int $userId, int $postId, string $newUrl, PDO $db): void
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

function deletePost(int $userId, int $postId, PDO $db): void
{
    $sql = [
        "DELETE FROM posts WHERE id = :post_id AND user_id = :user_id;",
        "DELETE FROM upvotes WHERE post_id = :post_id;",
        "DELETE FROM comments WHERE post_id = :post_id;",
    ];

    for ($i = 0; $i < sizeof($sql); $i++) {
        if ($i === 0) {
            $stmnt = $db->prepare($sql[$i]);
            $stmnt->bindParam(":post_id", $postId, PDO::PARAM_STR);
            $stmnt->bindParam(":user_id", $userId, PDO::PARAM_STR);
            $stmnt->execute();

            if (!$stmnt) {
                die(var_dump($db->errorInfo));
            }
        } else {
            $stmnt = $db->prepare($sql[$i]);
            $stmnt->bindParam(":post_id", $postId, PDO::PARAM_STR);
            $stmnt->execute();

            if (!$stmnt) {
                die(var_dump($db->errorInfo));
            }
        }
    }
}

function fetchLatestPostId(PDO $db): int
{
    $stmnt = $db->query("SELECT id FROM posts ORDER BY creation_time DESC LIMIT 1;");
    $stmnt->execute();
    $result = $stmnt->fetch(PDO::FETCH_ASSOC);
    return (int)$result['id'];
}

// addComment
function addComment(int $userId, int $postId, string $comment, PDO $db): void
{
    $time = date('Y-m-d H:i:s');
    $stmnt = $db->prepare("INSERT INTO comments (user_id, post_id, comment, creation_time) 
    VALUES(:user_id, :post_id, :comment, :creation_time)");
    $stmnt->bindParam(":user_id", $userId, PDO::PARAM_INT);
    $stmnt->bindParam(":post_id", $postId, PDO::PARAM_INT);
    $stmnt->bindParam(":comment", $comment, PDO::PARAM_STR);
    $stmnt->bindParam(":creation_time", $time, PDO::PARAM_STR);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

function addReply(int $userId, int $postId, int $commentId, string $comment, PDO $db): void
{
    $time = date('Y-m-d H:i:s');
    $stmnt = $db->prepare("INSERT INTO comments (post_id, user_id, comment, reply, creation_time)
    VALUES(:post_id, :user_id, :comment, :reply, :creation_time)");
    $stmnt->bindParam(":user_id", $userId, PDO::PARAM_INT);
    $stmnt->bindParam(":post_id", $postId, PDO::PARAM_INT);
    $stmnt->bindParam(":comment", $comment, PDO::PARAM_STR);
    $stmnt->bindParam(":reply", $commentId, PDO::PARAM_INT);
    $stmnt->bindParam(":creation_time", $time, PDO::PARAM_STR);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

// editComment
function editComment(int $userId, int $commentId, string $updatedComment, PDO $db): void
{
    $stmnt = $db->prepare("UPDATE comments SET comment = :updated_comment WHERE id = :comment_id AND user_id = :user_id");
    $stmnt->bindParam(":comment_id", $commentId, PDO::PARAM_INT);
    $stmnt->bindParam(":user_id", $userId, PDO::PARAM_INT);
    $stmnt->bindParam(":updated_comment", $updatedComment, PDO::PARAM_STR);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }
}

// deleteComment
function deleteComment(int $userId, int $commentId, PDO $db): void //fix until i figure out how to keep replies
{
    $sql = [
        "DELETE FROM comments WHERE id = :comment_id AND user_id = :user_id;",
        "DELETE FROM comments WHERE reply = :comment_id;",
    ];

    for ($i = 0; $i < sizeof($sql); $i++) {
        if ($i === 0) {
            $stmnt = $db->prepare($sql[$i]);
            $stmnt->bindParam(":user_id", $userId, PDO::PARAM_STR);
            $stmnt->bindParam(":comment_id", $commentId, PDO::PARAM_STR);
            $stmnt->execute();

            if (!$stmnt) {
                die(var_dump($db->errorInfo()));
            }
        }
        $stmnt = $db->prepare($sql[$i]);
        $stmnt->bindParam(":comment_id", $commentId, PDO::PARAM_STR);
        $stmnt->execute();
        if (!$stmnt) {
            die(var_dump($db->errorInfo()));
        }
    }
}

// print comment functions
function fetchComments(int $postId, PDO $db): ?array
{
    $stmnt = $db->prepare("SELECT * FROM comments WHERE post_id = :post_id AND reply IS NULL ORDER BY creation_time");
    $stmnt->bindParam(":post_id", $postId, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $results = $stmnt->fetchAll(PDO::FETCH_ASSOC);

    if (!$results) {
        return null;
    }

    return $results;
}

function fetchReplies($postId, $db): ?array
{
    $stmnt = $db->prepare("SELECT * FROM comments WHERE post_id = :post_id AND reply IS NOT NULL ORDER BY creation_time");
    $stmnt->bindParam(":post_id", $postId, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $results = $stmnt->fetchAll(PDO::FETCH_ASSOC);

    if (!$results) {
        return null;
    }

    return $results;
}

function fetchUserComments(int $userId, PDO $db): ?array
{
    $stmnt = $db->prepare("SELECT * FROM comments WHERE user_id = :user_id");
    $stmnt->bindParam(":user_id", $userId, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $results = $stmnt->fetchAll(PDO::FETCH_ASSOC);

    if (!$results) {
        return null;
    }

    return $results;
}

function fetchCommentForEdit(int $commentId, int $userId, PDO $db): ?array
{
    $stmnt = $db->prepare("SELECT * FROM comments WHERE id = :comment_id AND user_id = :user_id");
    $stmnt->bindParam(":comment_id", $commentId, PDO::PARAM_INT);
    $stmnt->bindParam(":user_id", $userId, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        return null;
    }

    return $result;
}

function fetchComment(int $commentId, PDO $db): ?array
{
    $stmnt = $db->prepare("SELECT * FROM comments WHERE id = :comment_id;");
    $stmnt->bindParam(":comment_id", $commentId, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        return null;
    }

    return $result;
}

// print posts functions
function fetchPost(int $postId, PDO $db): ?array
{
    $stmnt = $db->prepare("SELECT * FROM posts WHERE id = :post_id");
    $stmnt->bindParam(":post_id", $postId, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        return null;
    }

    return $result;
}

function fetchPoster(int $id, PDO $db): ?string
{
    $stmnt = $db->prepare("SELECT user_name FROM users WHERE id = :id;");
    $stmnt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        return null;
    }

    return $result['user_name'];
}

function fetchPostTitle(int $postId, PDO $db): ?string
{
    $stmnt = $db->prepare("SELECT title FROM posts WHERE id = :id;");
    $stmnt->bindParam(":id", $postId, PDO::PARAM_INT);
    $stmnt->execute();

    if (!$stmnt) {
        die(var_dump($db->errorInfo()));
    }

    $result = $stmnt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        return null;
    }

    return $result['title'];
}

function fetchPosts(int $page, PDO $db, bool $orderByUpvotes = false): array
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
        $sql = "SELECT 
        p.*,
        COALESCE(v.upvote_count, 0) AS upvotes,
        COALESCE(c.comment_count, 0) AS comments
        FROM posts p
        LEFT OUTER JOIN (
            SELECT post_id, COUNT(post_id) AS upvote_count
            FROM upvotes
            GROUP BY post_id
        ) v
        ON p.id = v.post_id
        LEFT OUTER JOIN (
            SELECT post_id, COUNT(post_id) AS comment_count
            FROM COMMENTS
            GROUP BY post_id
        ) c
        ON p.id = c.post_id
        ORDER BY upvotes DESC, p.creation_time DESC
        LIMIT 10 OFFSET :offset;";
    } else {
        $sql = "SELECT 
        p.*,
        COALESCE(v.upvote_count, 0) AS upvotes,
        COALESCE(c.comment_count, 0) AS comments
        FROM posts p
        LEFT OUTER JOIN (
            SELECT post_id, COUNT(post_id) AS upvote_count
            FROM upvotes
            GROUP BY post_id
        ) v
        ON p.id = v.post_id
        LEFT OUTER JOIN (
            SELECT post_id, COUNT(post_id) AS comment_count
            FROM COMMENTS
            GROUP BY post_id
        ) c
        ON p.id = c.post_id
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

function fetchTotalNumberOfPosts(PDO $db): int
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

function fetchUpvotes(int $postId, PDO $db): int
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

function userUpvote(int $userId, int $postId, PDO $db): bool
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

function toggleUpvote(int $userId, int $postId, PDO $db): void
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
