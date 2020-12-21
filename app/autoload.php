<?php

declare(strict_types=1);

session_start();
date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');
require __DIR__ . '/functions.php';
$config = require __DIR__ . '/config.php';
$db = new PDO($config['database_path']);
