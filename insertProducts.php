<?php
include 'config.php';

// Check if 'size' and 'description' columns exist in the 'products' table
$checkSizeColumnQuery = "SHOW COLUMNS FROM products LIKE 'size'";
$checkDescriptionColumnQuery = "SHOW COLUMNS FROM products LIKE 'description'";
$checkSizeColumnResult = $conn->query($checkSizeColumnQuery);
$checkDescriptionColumnResult = $conn->query($checkDescriptionColumnQuery);

if ($checkSizeColumnResult->num_rows == 0) {
    // Add 'size' column to 'products' table if it doesn't exist
    $addSizeColumnQuery = "ALTER TABLE products ADD COLUMN size VARCHAR(255)";
    if ($conn->query($addSizeColumnQuery) === FALSE) {
        error_log("Error adding column 'size': " . $conn->error);
    }
}

if ($checkDescriptionColumnResult->num_rows == 0) {
    // Add 'description' column to 'products' table if it doesn't exist
    $addDescriptionColumnQuery = "ALTER TABLE products ADD COLUMN description TEXT";
    if ($conn->query($addDescriptionColumnQuery) === FALSE) {
        error_log("Error adding column 'description': " . $conn->error);
    }
}

// Product data to insert
$products = [
    ["Nike Air Force 1 Low","The Air Force 1 model represents a modern take on the classic sneaker.",90,"img/airforce1low.webp","7,8,9,10,11",50],
    ["Nike Air Max 270", "The Nike Air Max 270 model's uniquely large heel is meant to maximize comfort.", 160.00, "img/airmax270.webp", "7,8,9,10,11", 40],
    ["Nike Air Max 97", "Nike Air Max 97 is known for its unique water-ripple line design.", 160.00, "img/airmax97.webp", "7,8,9,10,11", 30],
    ["Nike Air VaporMax Plus", "Based off the 1998 running shoe, the Nike Air VaporMax Plus is focused on comfort and style.", 200.00, "img/vapormaxplus.webp", "7,8,9,10,11", 20],
    ["Nike Revolution 5", "This running shoe comes in a variety of styles for men, women, and children.", 65.00, "img/revolution5.webp", "7,8,9,10,11", 60],
    ["Nike Air VaporMax Flyknit 3", "The Nike Air VaporMax Flyknit 3 is known for its breathable and stretchable material and its VaporMax technology that helps cushion the entire sole.", 200.00, "img/vapormaxflyknit3.webp", "7,8,9,10,11", 20],
    ["Adidas NMD R1", "These breathable sneakers from Adidas are inspired by the trends of the '80s.", 130.00, "img/nmdr1.webp", "7,8,9,10,11", 30],
    ["Jordan 13 Retro", "The Jordan 13 'Flint,' pictured above, recently became the fastest-selling sneaker in the history of StockX, a leading resale marketplace.", 190.00, "img/jordan13retro.webp", "7,8,9,10,11", 10],
    ["Jordan I High OG", "The Air Jordan 1s are considered one of the most iconic silhouettes to come from the Jordan Brand.", 170.00, "img/jordan1highog.webp", "7,8,9,10,11", 15],
    ["Nike Air Max 90", "The Nike Air Max 90 is a remake of the original silhouette known for its comfort, stitched overlays, and bright colors.", 120.00, "img/airmax90.webp", "7,8,9,10,11", 25]
];

$insertStmt = $conn->prepare("INSERT INTO products (name, description, price, img, size, stock) VALUES (?, ?, ?, ?, ?, ?)");
if ($insertStmt === false) {
    error_log("Error preparing statement: " . $conn->error);
}

foreach ($products as $product) {
    $stmt = $conn->prepare("SELECT id FROM products WHERE name = ?");
    $stmt->bind_param("s", $product[0]);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 0) {
        $insertStmt->bind_param("ssdssi", $product[0], $product[1], $product[2], $product[3], $product[4], $product[5]);
        if (!$insertStmt->execute()) {
            error_log("Error executing statement: " . $insertStmt->error);
        }
    } else {
        error_log("Product already exists: " . $product[0]);
    }
    $stmt->close();
}

$insertStmt->close();
?>
