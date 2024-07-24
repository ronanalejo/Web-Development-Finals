<?php
include 'session_check.php';
check_session();
session_start();
include 'config.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoleStealer - Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.png">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="productPage.php"><img src="img/logo.png" alt="Store Logo" class="navbar-logo"></a>
            <a href="productPage.php">Home</a>
            <div style="position: relative; display: flex; align-items: center;">
                <span id="userDisplay"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php">Logout</a>
                <img src="img/cart.png" id="cartButton" alt="Cart" style="height: 24px; width: 24px; cursor: pointer;">
                <span id="cartCount" class="cart-count"><?php echo array_sum(array_column($cart, 'quantity')); ?></span>
            </div>
        </div>
    </header>

    <main class="cart-container">
        <h1>Your Cart</h1>
        <?php if (!empty($cart)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item): ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($item['img']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width: 50px; height: 50px;"></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <form action="updateCart.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="action" value="increase">+</button>
                                    <button type="submit" name="action" value="decrease">-</button>
                                </form>
                                <form action="removeFromCart.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit">Remove</button>
                                </form>
                            </td>
                        </tr>
                        <?php $total += $item['price'] * $item['quantity']; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h2>Total: $<?php echo number_format($total, 2); ?></h2>
            <form action="checkout.php" method="POST">
                <button type="submit">Checkout</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </main>
</body>
</html>
