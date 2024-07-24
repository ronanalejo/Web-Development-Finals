<?php
include 'config.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Get order details from session
    $orderDetails = json_decode($_SESSION['orderDetails'], true);

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert order into orders table
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_date) VALUES (?, ?, NOW())");
        $stmt->bind_param("id", $user_id, $orderDetails['totalAmount']);
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert each order item
        foreach ($orderDetails['cartItems'] as $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }
            $stmt->close();

            // Update product stock
            $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $stmt->bind_param("ii", $item['quantity'], $item['id']);
            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }
            $stmt->close();
        }

        // Commit transaction
        $conn->commit();

        // Send email confirmation
        $to = $orderDetails['email'];
        $subject = "Order Confirmation";
        $message = "Hello " . $orderDetails['firstName'] . " " . $orderDetails['lastName'] . ",\n\n";
        $message .= "Thank you for your order. Here are your order details:\n";
        $message .= "Order ID: " . $order_id . "\n";
        $message .= "Order Date: " . date("Y-m-d H:i:s") . "\n";
        $message .= "Total Amount: $" . $orderDetails['totalAmount'] . "\n\n";
        $message .= "Items:\n";
        foreach ($orderDetails['cartItems'] as $item) {
            $message .= "- " . $item['name'] . " (Quantity: " . $item['quantity'] . ", Price: $" . $item['price'] . ")\n";
        }
        $message .= "\nThank you for shopping with us!\n";
        $headers = "From: no-reply@solestealer.com";

        mail($to, $subject, $message, $headers);

        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        echo "Order confirmed. Thank you for your purchase!";
        unset($_SESSION['orderDetails']); // Clear order details from session

    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo "Failed to process order: " . $e->getMessage();
    }
} else {
    echo "Please log in first.";
}
$conn->close();
?>
