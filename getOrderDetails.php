<?php
session_start();

$orderDetails = [
    'fullName' => $_SESSION['fullName'],
    'shippingAddress' => $_SESSION['shippingAddress'],
    'contactNumber' => $_SESSION['contactNumber']
];

echo json_encode($orderDetails);
?>
