<?php
include 'session_settings.php';

$orderDetails = json_decode(file_get_contents('php://input'), true);
$_SESSION['orderDetails'] = $orderDetails;
http_response_code(200);
?>
