<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Validate login against SQLite users table. Returns user row on success, null on failure.
 */
function login_user(string $email, string $password)
{
    require_once __DIR__ . '/database.php';
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        return null;
    }
    $stmt = $pdo->prepare("SELECT id, email, name, role, password_hash FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([trim($email)]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user || !password_verify($password, $user['password_hash'])) {
        return null;
    }
    unset($user['password_hash']);
    $_SESSION['user'] = $user;
    return $user;
}

/**
 * Register a new user (patient by default). Returns true on success, error message on failure.
 */
function register_user(string $name, string $email, string $password, string $role = 'patient')
{
    require_once __DIR__ . '/database.php';
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        return 'Database unavailable.';
    }
    $email = trim($email);
    $name = trim($name);
    if (strlen($password) < 6) {
        return 'Password must be at least 6 characters.';
    }
    $allowedRoles = ['admin', 'doctor', 'nurse', 'receptionist', 'patient'];
    if (!in_array($role, $allowedRoles, true)) {
        $role = 'patient';
    }
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, name, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$email, $passwordHash, $name, $role]);
        return true;
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'UNIQUE') !== false) {
            return 'That email is already registered.';
        }
        return 'Registration failed. Please try again.';
    }
}

function logout_user(): void
{
    $_SESSION = [];
    session_destroy();
}

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function current_role(): ?string
{
    $user = current_user();
    return $user['role'] ?? null;
}

function require_role(array $roles): void
{
    $role = current_role();
    if (!$role || !in_array($role, $roles, true)) {
        header('Location: index.php?page=login');
        exit;
    }
}
