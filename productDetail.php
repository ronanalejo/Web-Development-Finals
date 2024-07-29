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
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="icon" href="img/solemates_logo1.jpg">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="productPage.php"><img src="img/solemates_logo1.jpg" alt="Store Logo" class="navbar-logo"></a>
            <a id="home-btn" href="productPage.php">Home</a>
            <input type="text" id="searchBar" placeholder="Search here" oninput="showSuggestions()">
            <span id="search-svg"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="gray"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg></span>
            <div id="searchSuggestions" class="search-suggestions"></div>
            <div style="position: relative; display: flex; align-items: center;">
                <span id="userDisplay"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a id="logout-btn" href="logout.php">Logout</a>
                <img src="img/cart.png" id="cartButton" alt="Cart" style="height: 24px; width: 24px; cursor: pointer;">
                <span id="cartCount" class="cart-count">0</span>
            </div>
        </div>
    </header>

    <main class="product-detail">
        <div class="product-detail-card">
            <img src="<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            
            <div class="product-info">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
            <div class="size-selection">
            <label id="select-size-txt" for="size">Select Size: </label>

            <label>
                <input type="radio" name="radio" value="7">
                <span>7</span>
            </label>
            <label>
                <input type="radio" name="size" value="8">
                <span>8</span>
            </label>
            <label>
                <input type="radio" name="size" value="9">
                <span>9</span>
            </label>
            <label>
                <input type="radio" name="size" value="10">
                <span>10</span>
            </label>
            <label>
                <input type="radio" name="size" value="11">
                <span>11</span>
            </label>
            <label>
                <input type="radio" name="size" value="12">
                <span>12</span>
            </label>
            <!-- <input type="radio" name="size" value="7" checked> 7
            <input type="radio" name="size" value="8"> 8
            <input type="radio" name="size" value="9"> 9
            <input type="radio" name="size" value="10"> 10
            <input type="radio" name="size" value="11"> 11 -->
        </div> <br>

        <div class="quantity-selection">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1">
        </div>

        <button class="add-to-cart" data-id="<?= $product['id']; ?>" data-name="<?= $product['name']; ?>" data-price="<?= $product['price']; ?>" data-img="<?= $product['img']; ?>">Add to Cart</button>
    </div>

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

    <script>

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
    <script src="js/productPageScript.js"></script>
    <script src="js/cart.js"></script>
</body>
</html>

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
    margin: 0 155px;
    padding: 10px;
    border: 1px solid #DDD; 
    border-radius: 20px; 
    outline: none; 
}

span#search-svg {
    position: absolute;
    float: right;
    margin-left: 1090px;
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
    padding: 10px 20px 10px 20px;
    cursor: pointer;
    margin: 40px 20px 20px 0px;
    border-radius: 20px; 
    text-align: center;
    transition: 0.2s;
}
.add-to-cart:hover {
    opacity: 80%;
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
    background-color: red;
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

.product-detail-card {
    display: flex;
    align-items: top;
    gap: 50px;
    padding: 20px;
    justify-content: center;
    margin: 50px 0px 0px 200px;
}
.product-img {
    max-width: 100%;
    height: auto;
    margin-right: 200px;
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

#select-size-txt {
    margin-top: 5px;
    margin-right: 5px;
}

.size-select {
    border: black 1px;
    background-color: red;
}

.size-selection {
    display: flex;
    flex-wrap: wrap;
    margin-top: 0.5rem; 
    
}
.size-selection input[type="radio"] {
    clip: react(0 0 0 0);
    clip-path: inset(100%);
    height: 1px;
    overflow: hidden;
    position: absolute;
    white-space: nowrap;
    width: 1px;
}
.size-selection input[type="radio"]:checked + span {
    box-shadow: 0 0 0 0.0625em gray;
    background-color: #2c2b30;
    color: white;
}

:focus {
    outline: 0;
    border: black;
    box-shadow: 0 0 0 1px;
}

label span {
    display: flex;
    cursor: pointer;
    background-color: #FFF;
    padding: 0.375em 1em;
    position: relative;
    margin-left: .0625em;
    box-shadow: 0 0 0 0.0625em;
    letter-spacing: .05em;
    color: #3e4963;
    text-align: center;
    transition: background-color .2s ease;
}

label:first-child span {
  border-radius: .375em 0 0 .375em;
}

label:last-child span {
  border-radius: 0 .375em .375em 0;
}


/* ------------------------------------------------ */
/* ADD TO CART BUTTON SECTION */

.add-to-cart-btn {
    background-color: var(--primary--color); 
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
    margin: 0px 50px 0px 0px ;
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
/* PRODUCT DETAIL */

#quantity {
    max-width: 50px;
    padding: 5px 0px 5px 3px;
}

.quantity-selection {
    margin-right: 0px;
}

.quantity-input {
    width: 60px;
    padding: 50px;
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
    width: 5px;
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
    /* border: 1px solid #ccc; */
    padding: 0;
    font-size: 16px;
    height: 25px;
    line-height: 20px;
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
    border: 0px solid #ccc;
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
    margin: 0px 50px 0px 0px;
}

.cart-item-img {    
    width: 100px;
    margin-right: 20px;
    margin-bottom: -100px;
}

.cart-item-info {
    flex-grow: 1;
    font-size: 120px;
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
    margin-right: 200px;
    width: 1250px;
}


.shipping-info-container {
    margin-right: 0;
    flex: 1;
    margin-right: 20px;
    
}

.add-to-cart-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 1;
    transition: opacity 0.5s ease-out;
    z-index: 999; /* Ensure it is above other elements */
}

.add-to-cart-popup {
    background-color: #4caf50;
    color: white;
    padding: 20px 40px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    opacity: 1;
    transition: opacity 0.5s ease-out;
}

.add-to-cart-overlay.fade-out {
    opacity: 0;
}


</style>
