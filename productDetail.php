<?php
include 'session_check.php';
check_session();
include 'config.php';

// Retrieve and validate the product ID
if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);
} else {
    die("No product ID provided");
}

if ($productId <= 0) {
    die("Invalid product ID provided: " . htmlspecialchars($productId));
}

// Prepare and execute the query
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Error executing query: " . $stmt->error);
}

if ($result->num_rows == 0) {
    die("Product not found for ID: " . htmlspecialchars($productId));
}

$product = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - SoleStealer</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.png">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="productPage.php"><img src="img/logo.png" alt="Store Logo" class="navbar-logo"></a>
            <a href="productPage.php">Home</a>
            <input type="text" id="searchBar" placeholder="Search products..." oninput="showSuggestions()">
            <div id="searchSuggestions" class="search-suggestions"></div>
            <div style="position: relative; display: flex; align-items: center;">
                <span id="userDisplay"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php">Logout</a>
                <img src="img/cart.png" id="cartButton" alt="Cart" style="height: 24px; width: 24px; cursor: pointer;">
                <span id="cartCount" class="cart-count">0</span>
            </div>
        </div>
    </header>

    <main class="product-detail">
        <div class="product-detail-card">
            <img src="<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
            <p>Available Sizes: <?php echo htmlspecialchars($product['size']); ?></p>
            <p>Stock: <?php echo htmlspecialchars($product['stock']); ?></p>
            <button onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
        </div>
    </main>

    <div id="cartModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="order-form-container">
                <h1>Cart</h1>
                    <form id="cartModal" class="flex-item">
                        <section class="cart-summary">
                            <input type="checkbox" id="selectAll"> Select All</input>
                            <button id="deleteSelected" style="margin-left: 175px;">Delete Selected</button>
                            <div id="cartItems" class="cart-items"></div>
                        </section>
                    </form>
                </div>
        </div>
        <div class="modal-footer">
            <p>Total: <span id="cartTotal">$0</span></p>
            <a href="orderForm.html"><button class="checkout-btn" id="checkout">Checkout</button></a>
        </div>
     </div>

    <script>
        function addToCart(productId) {
            console.log('Adding product to cart:', productId);
            // Add AJAX or other logic to add product to cart
        }

        function showSuggestions() {
            const inputVal = document.getElementById('searchBar').value.toLowerCase();
            const suggestions = document.getElementById('searchSuggestions');
            suggestions.innerHTML = ''; 

            if (inputVal.length > 0) {
                fetch('getProducts.php')
                    .then(response => response.json())
                    .then(products => {
                        const filteredProducts = products.filter(product => product.name.toLowerCase().includes(inputVal));
                        suggestions.style.display = filteredProducts.length > 0 ? 'block' : 'none';
                        filteredProducts.forEach(product => {
                            const suggestionItem = document.createElement('div');
                            suggestionItem.className = 'search-suggestion-item';
                            suggestionItem.innerHTML = `
                                <img src="${product.img}" alt="${product.name}" style="width: 30px; height: 30px; object-fit: cover;">
                                <span style="margin-left: 10px; color: black;">${product.name} - $${product.price}</span>
                            `;
                            suggestionItem.addEventListener('click', () => {
                                window.location.href = `productDetail.php?id=${product.id}`;
                            });
                            suggestions.appendChild(suggestionItem);
                        });
                    });
            } else {
                suggestions.style.display = 'none';
            }
        }
    </script>
    <script src="js/script.js"></script>
    <script src="js/common.js"></script>
</body>
</html>
