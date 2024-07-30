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
    <title>SoleMates</title>
    <link rel="icon" href="img/solemates_logo1.jpg">
</head>
<body>

    <!-- HEADER SECTION -->

    <header>
        <div class="navbar">
            <a href="productPage.php"><img src="img/solemates_logo1.jpg" alt="Store Logo" class="navbar-logo"></a>
            <a id="home-btn" href="productPage.php">Home</a>
            <input type="text" id="searchBar" placeholder="Search..."  oninput="filterProducts()">
            <span id="search-svg"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="gray"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg></span>
            <div style="position: relative; display: flex; align-items: center;">
                <span id="userDisplay"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a id="logout-btn" href="logout.php">Logout</a>
                <img src="img/cart.png" id="cartButton" alt="Cart" style="height: 24px; width: 24px; cursor: pointer;">
                <span id="cartCount" class="cart-count">0</span>
            </div>
        </div>
    </header>

    <!-- MAIN SECTION -->

    <main id="productContainer" class="product-container">
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
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found</p>
        <?php endif; ?>
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
            <div>
            <br> Total Items: <span id="totalItems"></span> <br>
            <br> Total Price: $<span id="totalPrice"></span>
            </div>
            <a href="orderForm.php"><button class="checkout-btn" id="checkout">Checkout</button></a>
        </div>
     </div>

    <!-- SCRIPT SECTION -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('index.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (!response.ok) {
                    console.error('Failed to initialize index.php');
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        });

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

    <script src="js/productPageScript.js"></script>
    <script src="js/script.js"></script>
    <script src="js/common.js"></script>
    <script src="js/cart.js"></script>
</body>
</html>

<?php
$conn->close();
?>


<!-- --------------------------------------------------------------- -->
<!-- CSS SECTION -->

<style>
    :root {
    --primary--color: #2c2b30;
}

body, html {
    margin: 0;
    padding: 0;
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;

}

header {
    position: sticky;
    top: 0;
    z-index: 1;
  }

  /* ------------------------------------------------ */
 /* NAVIGATE MENU SECTION */

.navbar {
    background-color: var(--primary--color); 
    color: #fff; 
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 10%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
}

.navbar-logo {
    height: 60px;
    border-radius: 150px;
    margin-right: 30px;
}

.navbar a, #cartButton, #usernameDisplay {
    color: #fff;
    text-decoration: none;
    font-size: 16px;
    margin-left: 20px;
}
#cartButton {
    transition: 0.2s;
    margin-left: 50px;
}
#cartButton:hover {
    opacity: 65%;
}
.navbar a:hover, #cartButton:hover {
    color: var(--primary--color); 
}
#home-btn {
    transition: 0.2s;
}
#home-btn:hover {
    opacity: 80%;
}
#searchBar {
    flex-grow: 1;
    margin: 0 130px;
    padding: 10px;
    border: 1px solid #DDD; 
    border-radius: 20px; 
    outline: none; 
}

span#search-svg {
    position: absolute;
    float: right;
    margin-left: 1085px;
}

#logout-btn {
    transition: 0.2s;
    margin-left: 30px;
}
#logout-btn:hover {
    opacity: 65%;
}

/* ------------------------------------------------ */
/* PRODUCT CONTAINERS SECTION */

.product-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 50px 5%;
    transition: 0.2s;
    margin-top: 15px;
}
.product-card {
    width: 300px;
    border: 1px solid #E1E1E1; 
    border-radius: 8px; 
    overflow: hidden;
    background-color: #FFF;
    transition: 0.2s;
    justify-content: center;
    margin: 15px;
}
.product-card:hover {
    transform: translateY(-5px); 
    filter: brightness(95%);
}
.product-card img {
    width: 100%;
    height: 200px;
    object-fit: cover; 
}
.product-card h3, .product-card p, .product-card .price {
    padding: 0 20px;
    margin: 10px 0;
}
.product-card .price {
    font-weight: bold;
    color: var(--primary--color); 
}

/* ------------------------------------------------ */
/* ADD TO CART SECTION */

.add-to-cart {
    background-color: var(--primary--color);  
    color: #FFF;
    border: none;
    padding: 10px;
    cursor: pointer;
    width: calc(100% - 40px);
    margin: 20px;
    border-radius: 20px; 
    display: block;
    text-align: center;
}
.add-to-cart:hover {
    background-color: var(--primary--color); 
}

.addCartBtn {
    margin: 0px 0px 10px 20px;
}

/* ------------------------------------------------ */
/* CART SECTION */

.cart-items {
    color: black;
}
.cart-item {
    display: flex;
    align-items: center;
}
.cart-item img {
    width: 100px;
    margin-right: 20px;
}
.cart-total {
    text-align: right;
    font-size: 20px;
}

/* ------------------------------------------------ */
/* CHECK OUT BUTTON SECTION */

#checkoutButton {
    background-color: var(--primary--color); 
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    display: block;
    margin: 20px auto;
    font-size: 18px;
}
/* ------------------------------------------------ */
/* CART ICON SECTION */

.cart-icon {
    height: 50px;
    width: 50x;
    cursor: pointer;
    transition: transform 0.2s ease;
}
.cart-icon:hover {
    transform: scale(1.1); 
    opacity: 65%;
}
#cartIcon {
    width: 30px;
    cursor: pointer;
    border: black 1px;
}

/* ------------------------------------------------ */


button {
    background-color: var(--primary--color); 
    border: none;
    color: white;
    padding: 10px 15px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 5px;
}

/* ------------------------------------------------ */
/* CART COUNT SECTION */

#cartCount {
    position: absolute;
    top: -10px;
    right: -10px;
    background-color: rgba(255, 0, 0, 0.733);
    color: white;
    border-radius: 50%;
    padding: 0 5px;
}

/* ------------------------------------------------ */
/* PRODUCT DETAIL SECTION */

.product-detail {
    display: flex;
    align-items: top;
    gap: 20px;
    padding: 20px;
}
.product-img {
    max-width: 100%;
    height: auto;
}
.product-info {
    flex: 1;
    max-width: 50%;
    padding: 20px;
}

/* ------------------------------------------------ */
/* PRODUCT SIZES SECTION */

.sizes {
    margin: 20px 0;
}

.size-btn {
    padding: 10px 15px;
    margin: 5px;
    background-color: white;
    border: 1px solid #ccc;
    cursor: pointer;
    color: black;
}
.size-btn.active {
    background-color: var(--primary--color); 
    color: white;
}

/* ------------------------------------------------ */
/* ADD TO CART BUTTON SECTION */

.add-to-cart-btn {
    background-color: var(--primary--color); 
    border-color: var(--primary--color); 
}
.add-to-cart-btn:hover {
    background-color: var(--primary--color); 
    border-color: var(--primary--color); 
}
.navbar a, .navbar a:visited {
    color: white;
    text-decoration: none;
    padding-left: 20px;
}

.size-btn:hover {
   
    background-color: white;
    color: black;
}

.size-btn.active {
    background-color: var(--primary--color); 
    color: white;
}

.flex-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.flex-item {
    flex-basis: 48%;
}

.order-form-container {
    width: 100%;
}

@media (max-width: 768px) {
    .flex-container {
        flex-direction: column;
    }

    .flex-item {
        flex-basis: 100%;
    }
}

/* ------------------------------------------------ */
/* CART ITEM SECTION */

.cart-item {
    display: flex;
    align-items: center;
}

.cart-item-img {
    height: auto;
}

.cart-item-info {
    display: flex;
    flex-direction: column;
    margin-top: -10px;
}

/* ------------------------------------------------ */

.quantity-input {
    width: 60px;
    padding: 5px;
    margin-left: 10px;
    font-size: 1em;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 5px;
}

.quantity-controls button {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 25px;
    height: 25px;
    font-size: 16px;
    color: #fff;
    background-color: var(--primary--color); 
    border: none;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    text-align: center;
}

.quantity-controls input {
    width: 40px;
    text-align: center;
   
    border: 1px solid #ccc;
    padding: 0;
    font-size: 16px;
    height: 25px;
    line-height: 25px;
}

/* ------------------------------------------------ */

.cart-item-select {
    margin-right: 10px;
}

/* ------------------------------------------------ */

.delete-item-btn {
    background-color:  rgba(255, 0, 0, 0.733);
    color: white;
    border: none;
    cursor: pointer;
    padding: 5px 10px;
    margin-top: 10px;
    margin-bottom: 20px;
    width: 110px;
}


input[type="text"], input[type="tel"], select, textarea {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

/* ------------------------------------------------ */

button {
    width: 100%;
    background-color: var(--primary--color); 
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: var(--primary--color); 
}

/* ------------------------------------------------ */

.cart-item {
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
}

.cart-item-img {    
    width: 100px;
    margin-right: 20px;
    margin-bottom: -100px;
}

.cart-item-info {
    flex-grow: 1;
    font-size: 100px;
}

.cart-item-name {
    font-weight: bold;
    font-size: 18px;
    margin-bottom: -10px;
    margin-left: 150px;
}

/* ------------------------------------------------ */

.quantity-controls button {
    background-color: #e7e7e7;
    color: black;
    border: none;
    padding: 5px 10px;
}

.quantity-controls input {
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    width: 50px;
}

/* ------------------------------------------------ */

#selectAll, #deleteSelected {
    margin-bottom: 50px;
    background-color: #f2f2f2;
    color: #333;
}

#selectAll{
    margin-top: 20px;
}

/* ------------------------------------------------ */

#deleteSelected {
    margin-top: 10px;
    background-color: #c40202;
    color: white;
    transition: 0.1s;
}
#deleteSelected:hover {
    background-color: #ad0202;
}
/* #ad0202 */
/* ------------------------------------------------ */

.cart-summary {
    border: 1px solid #ddd;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
}


#cartTotal {
    font-size: 20px;
    font-weight: bold;
    color: var(--primary--color); 
}

/* ------------------------------------------------ */

button {
    width: auto;
    padding: 10px 15px;
}

/* ------------------------------------------------ */

.confirmation-popup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 20px;
    z-index: 9999;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.confirmation-message {
    margin-bottom: 10px;
}

.confirmation-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
}

/* ------------------------------------------------ */

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: var(--primary--color); 
    z-index: 9998;
}

product-info {
    position: -webkit-sticky;
    position: sticky;
    top: 20px;
    width: 250px;
    padding: 20px;
    background: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}


.product-page {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.product-image-section {
    flex: 1;
    text-align: center;
}

.product-img {
    max-width: 100%;
    height: auto;
}

/* ------------------------------------------------ */

.dropdown-container {
    text-align: center;
    margin-top: 20px;
}

.dropdown-button {
    background-color: #f8f8f8;
    border: 1px solid #eaeaea;
    padding: 10px;
    width: calc(100% - 22px);
    text-align: center;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    margin-bottom: 2px;
    font-weight: bold;
    color: black;
}
.dropdown-button:hover {
    background-color: black;
    border: 1px solid #eaeaea;
    padding: 10px;
    width: calc(100% - 22px);
    text-align: center;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    margin-bottom: 2px;
    font-weight: bold;
    color: white;
    transition: 0.2s;
}

.dropdown-button:after {
    content: '▼';
    float: right;
}

.dropdown-button.active:after {
    content: '▲';
}

.dropdown-content {
    display: none;
    padding: 10px;
    border: 1px solid #eaeaea;
    width: calc(100% - 22px);    
    border-radius: 5px;
    background: white;
    color: black;
}

/* ------------------------------------------------ */
/* SEARCH SECTION */

.search-suggestions {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background-color: white;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 5;
}

.search-suggestion-item {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #ddd;
    padding: 10px;
}

.search-suggestion-item:hover {
    background-color: #f6f6f6;
}

/* ------------------------------------------------ */

.suggestion-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    margin-right: 10px;
}

.suggestion-text {
    display: block;
    margin-left: 10px;
}

/* ------------------------------------------------ */

.modal {
    display: none;
    position: fixed;
    z-index: 0;
    right: 0;
    top: 0;
    height: 90%;
    overflow-x: hidden;
    width: 30%;
    background-color: #fefefe;
    top: 90px;
    box-shadow: 0 0 15px 0 black;
    transition: right 0.5s ease;
}

.modal.show {
    display: block;
    right: 0;
    transition: 0.3s;
}

.modal.hide {
    display: none;
    right: -30%;
}

.modal-content {
    margin: auto;
    padding: 50px;
    width: 85%;
    max-height: 71.5%;
    overflow-y: auto;
}

.modal-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 10px;
    position: sticky;
}

/* ------------------------------------------------ */

.close {
    color: #aaa;
    float: right;
    font-size: 30px;
    font-weight: bold;
    transition: 0.1s;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}


@media (max-width: 768px) {
    .modal-content {
        width: 80%;
        margin: 20% auto;
    }
}



.cart-modal-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* ------------------------------------------------ */
/* CHECK OUT BUTTON SECTION */

.checkout-btn, .cancel-btn {
    padding: 10px 20px;
    margin: 10px;
    border: none;
    cursor: pointer;
}

.checkout-btn {
    background-color: var(--primary--color); 
    color: white;
}

.order-confirmation-container {
    text-align: center;
}

#productMain {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: flex-start;
    padding: 20px;
    background-color: #fff;
    margin: 20px auto;
    max-width: 1200px;
}

.order-content-container {
    display: flex;
    justify-content: space-between;
    margin: 0 80px;
}

.cart-summary-container{
    flex: 1;
    margin-right: 20px;
    width: 1250px;
}


.shipping-info-container {
    margin-right: 0;
    flex: 1;
    margin-right: 20px;
    
}
</style>