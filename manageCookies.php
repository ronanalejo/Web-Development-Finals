<?php
// Set a cookie
function setCookieValue($name, $value, $expire) {
    setcookie($name, $value, time() + $expire, "/");
}

// Get a cookie value
function getCookieValue($name) {
    return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
}

// Delete a cookie
function deleteCookie($name) {
    setcookie($name, "", time() - 3600, "/");
}
?>
