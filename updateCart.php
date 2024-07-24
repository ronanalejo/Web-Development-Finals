<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$product_id])) {
        if ($action == 'increase') {
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        } elseif ($action == 'decrease') {
            if ($_SESSION['cart'][$product_id]['quantity'] > 1) {
                $_SESSION['cart'][$product_id]['quantity'] -= 1;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }

    header('Location: cart.php');
    exit();
} else {
    header('Location: cart.php');
    exit();
}
?>
