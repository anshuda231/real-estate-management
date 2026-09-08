<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'real_estate_portal');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHAR', 'utf8mb4');

function getDB(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_NAME,
            DB_CHAR
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log('[NestFinder DB Error] ' . $e->getMessage());
            die(renderDbError());
        }
    }

    return $pdo;
}

function renderDbError(): string
{
    return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Unavailable — NestFinder</title>
    <style>
        body{font-family:sans-serif;background:#f8f9fa;
             display:flex;align-items:center;justify-content:center;
             height:100vh;margin:0;}
        .box{background:#fff;padding:40px;border-radius:12px;
             box-shadow:0 4px 20px rgba(0,0,0,.1);text-align:center;
             max-width:480px;}
        .box h1{color:#e74c3c;font-size:1.8rem;margin-bottom:12px;}
        .box p{color:#555;line-height:1.6;}
        .box a{display:inline-block;margin-top:20px;padding:10px 24px;
               background:#2c6fad;color:#fff;border-radius:6px;
               text-decoration:none;}
    </style>
</head>
<body>
    <div class="box">
        <h1>Service Unavailable</h1>
        <p>We are unable to connect to the database right now.<br>
           Please try again in a few moments.</p>
        <a href="/">Go to Homepage</a>
    </div>
</body>
</html>';
}