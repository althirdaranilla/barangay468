<?php
// logout.php - Handles the logout process
session_start();

// Clear all session variables
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Optional: Add a logout message as URL parameter instead of cookie
$logout_message = urlencode('You have been successfully logged out.');

// Redirect to login page with logout message
header("Location: login.php?logout=success&msg=" . $logout_message);
exit();
?>