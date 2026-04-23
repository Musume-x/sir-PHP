<?php

require_once __DIR__ . '/../config/database.php';

if (!$pdo) {
    echo "Database unavailable.\n";
    exit(1);
}

$password = 'password';
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$staff = [
    ['email' => 'receptionist@medicare.com', 'name' => 'Receptionist Demo', 'role' => 'receptionist'],
];

foreach ($staff as $s) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$s['email']]);
    $existingId = $stmt->fetchColumn();

    if ($existingId) {
        $update = $pdo->prepare("UPDATE users SET password_hash = ?, name = ?, role = ? WHERE id = ?");
        $update->execute([$passwordHash, $s['name'], $s['role'], $existingId]);
        echo "Updated {$s['role']} account for {$s['email']} with new password.\n";
    } else {
        $insert = $pdo->prepare("INSERT INTO users (email, password_hash, name, role, department) VALUES (?, ?, ?, ?, ?)");
        $insert->execute([$s['email'], $passwordHash, $s['name'], $s['role'], null]);
        echo "Created {$s['role']} account for {$s['email']}.\n";
    }
}

echo "Done.\n";

