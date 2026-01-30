<?php
try {
    new PDO("mysql:host=localhost;dbname=login_db", "root", "");
    echo "Database connected successfully!";
} catch (PDOException $e) {
    echo $e->getMessage();
}
