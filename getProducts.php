<?php
include 'config.php';

$result = $conn->query("SELECT id, name, price, img FROM products");

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode($products);

$conn->close();
?>
