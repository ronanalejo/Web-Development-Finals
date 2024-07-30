<?php
include 'config.php';

if (isset($_GET['product_id']) && isset($_GET['size'])) {
    $productId = intval($_GET['product_id']);
    $size = $_GET['size'];

    $stmt = $conn->prepare("SELECT stock FROM product_stocks WHERE product_id = ? AND size = ?");
    $stmt->bind_param("is", $productId, $size);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $stock = $row['stock'];
        echo json_encode(['stock' => $stock > 0 ? $stock : 'Out of Stock!']);
    } else {
        echo json_encode(['stock' => 'Out of Stock!']);
    }

    $stmt->close();
}

$conn->close();
?>
