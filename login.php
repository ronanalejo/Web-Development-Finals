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
    <title>SoleMates</title>
    <link rel="icon" href="img/solemates_logo1.jpg">
</head>

<body id="background-img" background="img/solemates_bg2.jpg">
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

<!-- --------------------------------------------------------------- -->
<!-- CSS SECTION -->

<style>
    :root {
    --primary--color: rgb(36, 36, 36);
}


* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: sans-serif;
    background-color: #1b3d49;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100vh;
    width: 100%;

}



h1 {
    text-align: center;
}

.Logo {
    display: block;
    margin: 0 auto 20px;
    margin-top: 25px;
    max-width: 100%;
    width: 250px;
    height: auto;
    border-radius: 150px;
}

h1#welcome-text {
    margin-top: 10px;
    margin-bottom: 40px;
}

.form-container {
    width: 400px;
    margin: 30px auto;
    background-color: white;
    padding: 20px 20px 30px 20px;
    border-radius: 20px;
}

#loginForm {
    display: flex;
    flex-direction: column;
    
}

#loginForm input {
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 100px;
}

#loginForm button {
    padding: 12px 25px;
    background-color: var(--primary--color);
    color: #fff;
    border: none;
    border-radius: 100px;
    cursor: pointer;
    flex: 1; 
    margin: 15px 0px 15px 0px;
    transition: 0.2s;
    
}

.login-button button {
    font-family: inherit;
}

#loginForm button + button {
    margin-left: 10px;
}

#loginForm button:hover {
    opacity: 80%;
}


.popup {
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

.popup-message {
    margin-bottom: 10px;
}

.popup-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.signup-link {
    text-decoration: none;
    color: #007bff;
}
</style>