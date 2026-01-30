<?php

// Placeholder PDO connection (configure with your actual DB credentials)

$dbHost = 'localhost';
$dbName = 'medicare_clinic';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // For now, fail silently to keep front-end demo working
    $pdo = null;
}

