<?php
/**
 * Authentication & Role Middleware
 * Usage:
 *   requireAuth();                 // any logged-in user
 *   requireRole('parent');         // parent only
 *   requireRole('headmaster');     // headmaster only
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Ensure user is logged in
 */
function requireAuth(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php');
        exit;
    }
}

/**
 * Ensure user has a specific role
 */
function requireRole(string $role): void
{
    requireAuth();

    if (
        !isset($_SESSION['user']['role']) ||
        $_SESSION['user']['role'] !== $role
    ) {
        // logged in but wrong role
        header('Location: /unauthorized.php');
        exit;
    }
}

/**
 * Get logged-in user (or null)
 */
function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

