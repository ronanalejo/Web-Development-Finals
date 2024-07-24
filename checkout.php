<?php
session_start();
include 'session_check.php';
check_session();
include 'config.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Implement order processing logic here (e.g., saving the order to the database, sending confirmation emails, etc.)

// Clear the cart
unset($_SESSION['cart']);

header('Location: productPage.php');
exit();
?>
