<?php
include 'session_settings.php'; // Include session settings first
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['token'] = bin2hex(random_bytes(32)); // CSRF protection

            // Debug: Print session variables to verify they are set
            echo "Session Variables Set: ";
            echo "User ID: " . $_SESSION['user_id'] . " Username: " . $_SESSION['username'];

            header("Location: productPage.php");
            exit();
        } else {
            echo "Invalid credentials.";
        }
    } else {
        echo "Invalid credentials.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoleMates - Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" href="img/solemates_logo1.jpg">
</head>

<body background="img/solemates_bg2.webp">
    <img src="img/solemates_logo1.jpg" alt="Image" class="Logo">
    
    <div class="form-container">
    <h1 id="welcome-text" style="color: black;">Welcome!</h1>
        <form id="loginForm" method="POST" action="login.php">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <div class="button-container">
                <button type="submit" class="login-button">Log In</button>
                <br><br>
                <p>Don't have an account? <a href="signUp.php" class="signup-link">Sign Up</a></p>
            </div>
        </form>
    </div>

</body>
</html>
