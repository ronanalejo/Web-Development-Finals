<?php
include 'session_settings.php';

$orderDetails = isset($_SESSION['orderDetails']) ? $_SESSION['orderDetails'] : [];
echo json_encode($orderDetails);
?>
