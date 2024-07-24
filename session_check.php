<?php
include 'session_settings.php'; // Include session settings first

function check_session() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Implement session timeout
    $timeout_duration = 1800; // 30 minutes

    if (isset($_SESSION['LAST_ACTIVITY']) && 
        (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
    $_SESSION['LAST_ACTIVITY'] = time();

    // Regenerate session ID periodically
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } else if (time() - $_SESSION['created'] > $timeout_duration) {
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}
?>
