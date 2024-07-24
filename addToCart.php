<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $product_name = $_POST['product_name'];
    $product_price = floatval($_POST['product_price']);
    $product_img = $_POST['product_img'];

    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] += 1;
    } else {
        $cart[$product_id] = [
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'img' => $product_img,
            'quantity' => 1
        ];
    }

    $_SESSION['cart'] = $cart;

    header('Location: productPage.php');
    exit();
} else {
    header('Location: productPage.php');
    exit();
}
?>
