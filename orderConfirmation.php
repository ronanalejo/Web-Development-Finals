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

    <div id="order-form-container">
        <h1 style="text-align: center;">Thank you for Ordering!</h1>
        <div id="flex-container">
            <h2>Recipient Information</h2>
            <p>Recipient Name: <span id="fullName"></span></p>
            <p>Shipping Address: <span id="shippingAddress"></span></p>
            <p>Contact Number: <span id="contactNumber"></span></p>
        </div>
        
        <h2>Order Summary</h2>
        <div id="cartItems"></div>
        <p>Total: <span id="cartTotal">0.00</span></p>

        <a href="productPage.php" id="backHomeButton" class="backButton">Back Home</a>
    </div>
    
    <script src="js/checkLogin.js"></script>
    <script src="js/orderConfirmationScript.js"></script>
</body>

<style>
        #order-form-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333;
            text-align: center;
        }

        #flex-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        #flex-container p {
            margin: 0;
        }

        #cartItems {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        #backHomeButton {
            display: block;
            text-align: center;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        #backHomeButton:hover {
            background-color: #555;
        }

        #productInfo{
            margin-left: 320px;
        }
                
    </style>

</html>
