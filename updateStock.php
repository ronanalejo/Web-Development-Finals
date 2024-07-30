<?php
include 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

$productId = $data['productId'];
$productSize = $data['productSize'];
$quantity = $data['quantity'];

$updateStockQuery = "
UPDATE product_stocks 
SET stock = stock - ? 
WHERE product_id = ? AND size = ? AND stock >= ?";

$stmt = $conn->prepare($updateStockQuery);
$stmt->bind_param("iiis", $quantity, $productId, $productSize, $quantity);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Insufficient stock']);
}

$stmt->close();
$conn->close();
?>
