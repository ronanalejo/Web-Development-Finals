<?php
// Function to perform aggregate queries
function performAggregateQueries($conn) {
    $queries = [
        "SELECT COUNT(*) as totalProducts FROM products",
        "SELECT AVG(price) as averagePrice FROM products",
        "SELECT SUM(quantity) as totalQuantity FROM orders",
        "SELECT MAX(price) as maxPrice FROM products",
        "SELECT MIN(price) as minPrice FROM products"
    ];

    foreach ($queries as $query) {
        $result = mysqli_query($conn, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            echo "Result: " . json_encode($row) . "<br>";
        } else {
            echo "Error: " . mysqli_error($conn) . "<br>";
        }
    }
}
?>
