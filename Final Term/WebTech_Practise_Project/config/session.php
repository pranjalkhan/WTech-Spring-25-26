<?php
// ─────────────────────────────────────────────────────────────────────────────
// config/session.php
// Starts the session (safe to call multiple times) and provides auth helpers.
// Include this at the top of EVERY controller that requires authentication.
// ─────────────────────────────────────────────────────────────────────────────

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Redirect unauthenticated visitors to the login page.
 * Adjust the redirect path to match where Task 1's login.php lives.
 */
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../Task1/View/login.php");
        exit();
    }
}

/**
 * Require a specific role. Redirects if the logged-in user has a different role.
 * @param string $role  'student' | 'instructor' | 'admin'
 */
function requireRole(string $role) {
    requireAuth();
    if ($_SESSION['role'] !== $role) {
        header("Location: ../../Task1/View/login.php");
        exit();
    }
}

/**
 * Check whether the current session user has a given role.
 * @param string $role
 * @return bool
 */
function hasRole(string $role): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}
