<?php
session_start();
require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

$firstName = $data['firstName'];
$lastName = $data['lastName'];
$fullName = $firstName . ' ' . $lastName;
$shippingAddress = $data['shippingAddress'];
$contactNumber = $data['contactNumber'];
$cartItems = $data['cartItems'];

$_SESSION['firstName'] = $data['firstName'];
$_SESSION['lastName'] = $data['lastName'];
$_SESSION['fullName'] = $fullName;
$_SESSION['shippingAddress'] = $data['shippingAddress'];
$_SESSION['contactNumber'] = $data['contactNumber'];

if ($firstName && $lastName && $shippingAddress && $contactNumber) {
    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert order details
        $stmt = $conn->prepare("INSERT INTO orders (first_name, last_name, full_name, shipping_address, contact_number) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("sssss", $firstName, $lastName, $fullName, $shippingAddress, $contactNumber);
        if (!$stmt->execute()) {
            throw new Exception('Execute failed: ' . htmlspecialchars($stmt->error));
        }

        // Get the last inserted order ID
        $orderId = $conn->insert_id;

        // Insert cart items
        $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        if ($itemStmt === false) {
            throw new Exception('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        foreach ($cartItems as $item) {
            $productId = $item['id'];
            $quantity = $item['quantity'];
            $price = $item['price'];

            $itemStmt->bind_param("iiid", $orderId, $productId, $quantity, $price);
            if (!$itemStmt->execute()) {
                throw new Exception('Execute failed: ' . htmlspecialchars($itemStmt->error));
            }
        }

        // Commit the transaction
        $conn->commit();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

    $stmt->close();
    $itemStmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
}

$conn->close();
?>
