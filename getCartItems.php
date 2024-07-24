<?php
include 'session_settings.php';

$cartItems = isset($_SESSION['cartItems']) ? $_SESSION['cartItems'] : [];
echo json_encode($cartItems);
?>
