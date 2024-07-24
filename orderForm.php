<?php
include 'session_check.php';
check_session();
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoleStealer - Order Form</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.png">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php"><img src="img/logo.png" alt="Store Logo" class="navbar-logo"></a>
            <a href="index.php">Home</a>
            <span id="userDisplay"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <img src="img/cart.png" id="cartButton" alt="Cart" style="height: 24px; width: 24px; cursor: pointer;">
            <span id="cartCount" class="cart-count">0</span>
        </div>
    </header>

    <main>
        <h1>Order Form</h1>
        <form action="process_order.php" method="POST">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required>
            <br>
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required>
            <br>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <br>
            <label for="contactNumber">Contact Number:</label>
            <input type="tel" id="contactNumber" name="contactNumber" required>
            <br>
            <input type="submit" value="Submit Order">
        </form>
    </main>
</body>
</html>
