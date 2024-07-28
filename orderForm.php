<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SoleMates</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="img/solemates_logo1.jpg">
    </head>
<body>
    <header>
        <div class="navbar">
            <a href="productPage.php"><img src="img/solemates_logo1.jpg" alt="Store Logo" class="navbar-logo"></a>
            <a id="home-btn" href="productPage.php">Home</a>
            <input type="text" id="searchBar" placeholder="Search..." oninput="showSuggestions()">
            <span id="search-svg"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="gray"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg></span>
            <div id="searchSuggestions" class="search-suggestions"></div>
            <div style="position: relative; display: flex; align-items: center;">
                <span id="userDisplay"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a id="logout-btn" href="logout.php">Logout</a>
            </div>
        </div>
    </header>
    <main class="order-form-container">
        <h1 style="margin: 20px 0px 20px 80px;">Order Details</h1>
        <div class="flex-container">
            <form id="orderForm" class="order-content-container">
                <div class="cart-summary-container">
                    <section class="cart-summary">
                        <h2>Cart Summary</h2>
                        <div id="cartItems" class="cart-items"></div>
                        <p>Total: <span id="cartTotal">$0</span></p>
                    </section>
                </div>
                <div class="shipping-info-container">
                    <section class="shipping-info">
                        <h2>Shipping Information</h2>
                        <input type="text" id="firstName" name="firstName" placeholder="First Name" required>
                        <input type="text" id="lastName" name="lastName" placeholder="Last Name" required>
                        <input type="text" id="shippingAddress" name="shippingAddress" placeholder="Shipping Address" required>
                        <input type="text" id="contactNumber" name="contactNumber" placeholder="Contact Number" required>
                        <div class="form-buttons">
                            <button type="button" id="cancelOrder" onclick="window.location.href='productPage.php'">Cancel</button>
                            <button type="submit" class="place-order-btn">Place Order</button>
                        </div>
                    </section>
                </div>
            </form>
        </div>
    </main>
    <script src="js/checkLogin.js"></script>
    <script src="js/orderFormScript.js"></script>
    <script src="js/common.js"></script>
    <script src="js/script.js"></script>
</body>

</html>
