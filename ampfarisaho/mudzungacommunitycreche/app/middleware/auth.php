<?php
/**
 * Authentication & Role Middleware
 * Usage:
 *   requireAuth();                 // any logged-in user
 *   requireRole('parent');         // parent only
 *   requireRole('headmaster');     // headmaster only
 *   currentUser();                 // get logged-in user
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Ensure user is logged in
 *
 * @return void
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
 *
 * @param string $role Role required (e.g., 'parent', 'headmaster')
 * @return void
 */
function requireRole(string $role): void
{
    requireAuth();

    $userRole = $_SESSION['user']['role'] ?? '';

    if ($userRole !== $role) {
        // Optionally, you can show a nice message before redirect
        $_SESSION['auth_error'] = "Access denied: {$role} role required.";
        header('Location: /unauthorized.php');
        exit;
    }
}

/**
 * Get the currently logged-in user or null if not logged in
 *
 * @return array|null
 */
function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

/**
 * Check if user has a specific role
 *
 * @param string $role
 * @return bool
 */
function isRole(string $role): bool
{
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === $role;
}

/**
 * Log out current user
 *
 * @return void
 */
function logout(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_unset();
        session_destroy();
    }
    header('Location: /login.php');
    exit;
}


