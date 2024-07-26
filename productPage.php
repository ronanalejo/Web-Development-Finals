<?php
include 'session_check.php';
check_session();
include 'config.php';

// Run insertProducts.php to ensure products are inserted
include 'insertProducts.php';

$result = $conn->query("SELECT * FROM products");

if ($result === false) {
    die("Error executing query: " . $conn->error);
}

$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoleMates - Product Page</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.png">
</head>
<body>

    <!-- HEADER SECTION -->

    <header>
        <div class="navbar">
            <a href="productPage.php"><img src="img/solemates_logo1.jpg" alt="Store Logo" class="navbar-logo"></a>
            <a id="home-btn" href="productPage.php">Home</a>
            <input type="text" id="searchBar" placeholder="Search products..." oninput="filterProducts()">
            <div style="position: relative; display: flex; align-items: center;">
                <span id="userDisplay"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php">Logout</a>
                <img src="img/cart.png" id="cartButton" alt="Cart" style="height: 24px; width: 24px; cursor: pointer;">
                <span id="cartCount" class="cart-count">0</span>
            </div>
        </div>
    </header>

    <!-- MAIN SECTION -->

    <main id="productContainer" class="product-container">
        <div id="cartItems" class="cart-items"></div> <!-- Ensure this element is present -->
        <div id="cartTotal" class="cart-total"></div> <!-- Ensure this element is present -->
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class='product-card' data-product-id='<?php echo htmlspecialchars($product['id']); ?>' data-product-name='<?php echo htmlspecialchars($product['name']); ?>'>
                    <img src='<?php echo htmlspecialchars($product['img']); ?>' alt='<?php echo htmlspecialchars($product['name']); ?>' />
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p>$<?php echo htmlspecialchars($product['price']); ?></p>
                    <form class="addCartBtn" action="addToCart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                        <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                        <input type="hidden" name="product_img" value="<?php echo htmlspecialchars($product['img']); ?>">
                        <button type="submit" class="add-to-cart-button">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found</p>
        <?php endif; ?>
    </main>

    <!-- SCRIPT SECTION -->

    <script src="js/productPageScript.js"></script>
    <script src="js/script.js"></script>
    <script>
        function filterProducts() {
            const query = document.getElementById('searchBar').value.toLowerCase();
            const products = document.querySelectorAll('.product-card');
            products.forEach(product => {
                const name = product.getAttribute('data-product-name').toLowerCase();
                if (name.includes(query)) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
