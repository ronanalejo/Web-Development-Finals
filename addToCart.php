<?php
session_start();

// Check if the cart exists, if not create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add item to cart
if (isset($_POST['addToCart'])) {
    $productId = $_POST['productId'];
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $productSize = $_POST['productSize'];
    $productImg = $_POST['productImg'];
    $productQuantity = 1; // Default quantity

    // Create an associative array for the item
    $item = array(
        'id' => $productId,
        'name' => $productName,
        'price' => $productPrice,
        'size' => $productSize,
        'img' => $productImg,
        'quantity' => $productQuantity
    );

    // Add item to the session cart
    array_push($_SESSION['cart'], $item);

    // Response
    echo json_encode(array('status' => 'success', 'message' => 'Item added to cart successfully'));
    exit();
}
?>
