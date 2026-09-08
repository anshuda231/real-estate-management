<?php
require_once 'config/db.php';

$plainPassword = 'Test@1234';
$hash          = password_hash($plainPassword, PASSWORD_BCRYPT);

$pdo  = getDB();
$stmt = $pdo->prepare("UPDATE users SET password = ?");
$stmt->execute([$hash]);
$count = $stmt->rowCount();

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Setup</title>
    <style>
        body{font-family:sans-serif;background:#f0f4f8;
             display:flex;align-items:center;
             justify-content:center;height:100vh;margin:0;}
        .box{background:#fff;padding:40px;border-radius:12px;
             box-shadow:0 4px 20px rgba(0,0,0,.1);text-align:center;}
        h1{color:#27ae60;}
        code{background:#f0f0f0;padding:4px 10px;
             border-radius:4px;font-size:1.1rem;}
        .warn{margin-top:20px;color:#e74c3c;font-weight:bold;}
    </style>
</head>
<body>
    <div class="box">
        <h1>✅ Passwords Updated!</h1>
        <p><strong>' . $count . '</strong> users update hue.</p>
        <p>Ab sab login kar sakte hain:<br>
        Password: <code>Test@1234</code></p>
        <p class="warn">⚠️ Ab yeh file DELETE kar do!</p>
    </div>
</body>
</html>';