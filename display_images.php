<?php
include 'config.php';

$result = $conn->query("SELECT name, img FROM products");

if ($result->num_rows > 0) {
    echo "<h1>Product Images</h1>";
    while ($row = $result->fetch_assoc()) {
        echo "<div>
                <h3>" . $row['name'] . "</h3>
                <img src='" . $row['img'] . "' alt='" . $row['name'] . "' style='width:200px;height:auto;' />
              </div>";
    }
} else {
    echo "No products found";
}

$conn->close();
?>
