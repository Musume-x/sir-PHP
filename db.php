<?php
$host = "localhost";
$dbname = "login_db";   // change if you used another database name
$user = "root";         // default XAMPP MySQL user
$pass = "";             // default XAMPP MySQL password is empty

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Database connection failed");
}