<?php
// Set session settings before starting the session
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Ensure this is enabled if using HTTPS
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_lifetime', 0);
ini_set('session.cookie_samesite', 'Strict');

// Ensure the session is started only once
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
