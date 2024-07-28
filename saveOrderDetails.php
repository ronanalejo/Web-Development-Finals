<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

$firstName = $data['firstName'];
$lastName = $data['lastName'];
$fullName = $firstName . ' ' . $lastName;

$_SESSION['firstName'] = $data['firstName'];
$_SESSION['lastName'] = $data['lastName'];
$_SESSION['fullName'] = $fullName;
$_SESSION['shippingAddress'] = $data['shippingAddress'];
$_SESSION['contactNumber'] = $data['contactNumber'];

echo json_encode(['status' => 'success']);
?>
