<?php

declare(strict_types=1);

require_once APP_ROOT . '/config/session.php';

/**
 * Memeriksa apakah pengguna sudah login.
 */
function isAuthenticated(): bool
{
    return isset($_SESSION['user']['id']);
}

/**
 * Memastikan pengguna sudah login.
 */
function requireAuth(): void
{
    if (!isAuthenticated()) {
        header('Location: ' . APP_URL . '/login.php');
        exit;
    }
}

/**
 * Memeriksa apakah pengguna memiliki role tertentu.
 */
function requireRole(array $allowedRoles): void
{
    requireAuth();

    $userRole = $_SESSION['user']['role'] ?? null;

    if (!in_array($userRole, $allowedRoles, true)) {
        http_response_code(403);

        require APP_ROOT . '/app/Views/errors/403.php';
        exit;
    }
}

/**
 * Mengambil informasi pengguna yang sedang login.
 */
function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}