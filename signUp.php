<?php
include 'session_settings.php'; // Include session settings first
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['newUsername'];
    $password = $_POST['newPassword'];

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Username already exists. Please choose a different username.";
    } else {
        $stmt->close();
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            // Automatically log the user in after successful registration
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['username'] = $username;
            $_SESSION['token'] = bin2hex(random_bytes(32)); // CSRF protection
            header("Location: productPage.php"); // Redirect to the product page
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoleStealer - Sign Up</title>
    <link rel="stylesheet" href="css/signup.css">
    <link rel="icon" href="img/logo.png">
</head>
<body>
    <img src="img/favicon.png" alt="Image" class="Logo">
    <h1>Welcome to SoleStealer</h1>
    <div class="form-container">
        <?php
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        ?>
        <form id="signUpForm" action="signUp.php" method="POST">
            <input type="text" id="newUsername" name="newUsername" placeholder="Enter Username" required><br>
            <input type="password" id="newPassword" name="newPassword" placeholder="Password" required><br>
            <button type="submit">Sign Up</button><br>
            <p>Have an account? <a href="login.php" class="login-link">Log In</a></p>
        </form>
    </div>
</body>
</html>
