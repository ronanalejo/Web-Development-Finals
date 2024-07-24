<?php
include 'session_check.php';
check_session();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token
    if (!isset($_POST['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Invalid CSRF token");
    }

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $contactNumber = $_POST['contactNumber'];
    $user_id = $_SESSION['user_id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert order into orders table
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_date) VALUES (?, ?, NOW())");
        $total_amount = 0; // Calculate this based on cart items later
        $stmt->bind_param("id", $user_id, $total_amount);
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Fetch cart items
        $cart_items = $conn->query("SELECT product_id, quantity, price FROM cart WHERE user_id = $user_id");
        if ($cart_items->num_rows > 0) {
            while ($item = $cart_items->fetch_assoc()) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $total_amount += $price * $quantity;

                // Insert order items
                $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
                if (!$stmt->execute()) {
                    throw new Exception($stmt->error);
                }
                $stmt->close();

                // Update product stock
                $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $stmt->bind_param("ii", $quantity, $product_id);
                if (!$stmt->execute()) {
                    throw new Exception($stmt->error);
                }
                $stmt->close();
            }
        } else {
            throw new Exception("No items in cart");
        }

        // Update total amount in orders table
        $stmt = $conn->prepare("UPDATE orders SET total_amount = ? WHERE id = ?");
        $stmt->bind_param("di", $total_amount, $order_id);
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $stmt->close();

        // Commit transaction
        $conn->commit();

        // Send email confirmation
        $to = $user_email; // Fetch user email from users table
        $subject = "Order Confirmation";
        $message = "Hello $firstName $lastName,\n\nThank you for your order. Here are your order details:\n";
        $message .= "Order ID: $order_id\n";
        $message .= "Order Date: " . date("Y-m-d H:i:s") . "\n";
        $message .= "Total Amount: $$total_amount\n\n";
        $message .= "Items:\n";
        foreach ($cart_items as $item) {
            $message .= "- {$item['name']} (Quantity: {$item['quantity']}, Price: $ {$item['price']})\n";
        }
        $message .= "\nThank you for shopping with us!\n";
        $headers = "From: no-reply@solestealer.com";

        mail($to, $subject, $message, $headers);

        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        $stmt->close();

        echo "Order confirmed. Thank you for your purchase!";
        unset($_SESSION['orderDetails']); // Clear order details from session

    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo "Failed to process order: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
