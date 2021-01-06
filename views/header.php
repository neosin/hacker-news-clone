<?php
if (userLoggedIn()) {
    setUserData($_SESSION['user'], $db);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/../assets/css/main.css">
    <title><?= $config['title'] ?></title>
</head>

<body>

    <?php require __DIR__ . '/navigation.php' ?>