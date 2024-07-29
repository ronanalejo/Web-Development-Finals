<?php
require 'config.php';
include 'manageCookies.php';
include 'fileHandler.php'; 
include 'aggregateFunctions.php';
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$dbname = "solemates";
$sql = "CREATE DATABASE IF NOT EXISTS " . $dbname;
if ($conn->query($sql) === TRUE) {
    echo "Database created or already exists. ";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create products table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    img VARCHAR(255) NOT NULL,
    sizes VARCHAR(255),
    stock INT NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Products table created successfully. ";
} else {
    die("Error creating products table: " . $conn->error);
}

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Users table created successfully. ";
} else {
    die("Error creating users table: " . $conn->error);
}

// Create orders table if it doesn't exist

// kailangan palitan to hindi ko mapalitan pero ang ginawa ko na lang nag sql ako sa phpmyadmin

//CREATE TABLE orders (
//    id INT AUTO_INCREMENT PRIMARY KEY,
//    first_name VARCHAR(255) NOT NULL,
//    last_name VARCHAR(255) NOT NULL,
//    full_name VARCHAR(255) NOT NULL,
//    shipping_address VARCHAR(255) NOT NULL,
//    contact_number VARCHAR(255) NOT NULL,
//    product_id INT NOT NULL
//);


$sql = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    shipping_address VARCHAR(255) NOT NULL,
    contact_number VARCHAR(255) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Orders table created successfully. ";
} else {
    die("Error creating orders table: " . $conn->error);
}

// Create order_items table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
)";
if ($conn->query($sql) === TRUE) {
    echo "Order items table created successfully. ";
} else {
    die("Error creating order items table: " . $conn->error);
}

// Create cart table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
)";
if ($conn->query($sql) === TRUE) {
    echo "Cart table created successfully. ";
} else {
    die("Error creating cart table: " . $conn->error);
}

$conn->close();

setCookieValue("userVisit", "Visited", 86400); // Set a cookie for 1 day
echo "User Visit Cookie: " . getCookieValue("userVisit") . "<br>";

// Perform aggregate queries and display the results
performAggregateQueries($conn);


// Redirect to product page
header("Location: productPage.php");
exit();

?>
