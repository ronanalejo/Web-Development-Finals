<?php
session_start();
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

$firstName = $data['firstName'];
$lastName = $data['lastName'];
$fullName = $firstName . ' ' . $lastName;
$shippingAddress = $data['shippingAddress'];
$contactNumber = $data['contactNumber'];

$_SESSION['firstName'] = $data['firstName'];
$_SESSION['lastName'] = $data['lastName'];
$_SESSION['fullName'] = $fullName;
$_SESSION['shippingAddress'] = $data['shippingAddress'];
$_SESSION['contactNumber'] = $data['contactNumber'];

if ($firstName && $lastName && $shippingAddress && $contactNumber) {
    $stmt = $conn->prepare("INSERT INTO orders (first_name, last_name, full_name, shipping_address, contact_number) VALUES (?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die(json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . htmlspecialchars($conn->error)]));
    }

    $stmt->bind_param("sssss", $firstName, $lastName, $fullName, $shippingAddress, $contactNumber);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Execute failed: ' . htmlspecialchars($stmt->error)]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
}

$conn->close();
?>
