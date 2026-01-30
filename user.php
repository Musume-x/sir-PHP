<?php
require 'db.php';

$username = 'testuser';
$passwordPlain = 'test123';

$hash = password_hash($passwordPlain, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)
    ON DUPLICATE KEY UPDATE password = VALUES(password)");
$stmt->execute([$username, $hash]);

echo "User created/updated. Username: $username, Password: $passwordPlain";