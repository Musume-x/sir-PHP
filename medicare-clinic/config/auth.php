<?php

if (session_status() === PHP_SESSION_NONE) {
    // Ensure we have a local session save path to avoid XAMPP folder errors
    $sessionPath = __DIR__ . '/../data/sessions';
    if (!is_dir($sessionPath)) {
        mkdir($sessionPath, 0755, true);
    }
    session_save_path($sessionPath);

    $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Basic session management: inactivity timeout and ID regeneration
$now = time();
// Inactivity timeout: 30 minutes
$sessionTimeout = 1800;
// Regenerate session ID every 10 minutes to reduce fixation risk
$regenerateInterval = 600;

// Inactivity handling
if (isset($_SESSION['last_activity']) && ($now - $_SESSION['last_activity']) > $sessionTimeout) {
    // Session expired due to inactivity
    $_SESSION = [];
    session_destroy();
    header('Location: index.php?page=login&timeout=1');
    exit;
}
$_SESSION['last_activity'] = $now;

// Session ID regeneration
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = $now;
} elseif (($now - $_SESSION['created']) > $regenerateInterval) {
    session_regenerate_id(true);
    $_SESSION['created'] = $now;
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
    session_regenerate_id(true);
    unset($user['password_hash']);
    $_SESSION['user'] = $user;
    return $user;
}

/**
 * Register a new user (patient by default). Returns true on success, error message on failure.
 */
function register_user(string $name, string $email, string $password, string $role = 'patient', ?string $department = null)
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
    $allowedRoles = ['admin', 'doctor', 'receptionist', 'patient'];
    if (!in_array($role, $allowedRoles, true)) {
        $role = 'patient';
    }
    $department = $department !== null ? trim($department) : null;
    if ($department === '') {
        $department = null;
    }
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, name, role, department) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$email, $passwordHash, $name, $role, $department]);
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
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
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
