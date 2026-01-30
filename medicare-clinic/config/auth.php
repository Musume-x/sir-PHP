<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function login_demo_user(string $role): void
{
    $_SESSION['user'] = [
        'id'   => 1,
        'name' => ucfirst($role) . ' Demo',
        'role' => $role,
    ];
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

