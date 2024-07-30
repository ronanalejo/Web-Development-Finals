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
    <title>SoleMates</title>
    <link rel="icon" href="img/solemates_logo1.jpg">
</head>
<body id="background-img" background="img/solemates_bg2.jpg">
    <img src="img/solemates_logo1.jpg" alt="Image" class="Logo">
    <h1 style="color: white;" >Welcome to Solemates</h1>
    <div class="form-container">
    <h3 id="create-text" style="color: black;" >Create Account</h3>
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

.form-container {
    width: 400px;
    margin: 30px auto;
    margin-bottom: 200px;
    background-color: #fff;
    padding: 20px;
    border-radius: 20px;
}

#signUpForm {
    display: flex;
    flex-direction: column;
}

#signUpForm input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 100px;
}

#signUpForm button {
    padding: 10px 15px;
    background-color: var(--primary--color);
    color: #fff;
    border: none;
    border-radius: 100px;
    cursor: pointer;
    transition: 0.2s;
}

#signUpForm button:hover {
    opacity: 80%;
}

h3#create-text {
    margin: 10px 0px 35px 108px;
}


button {
    width: 100%;
    background-color: #1b3d49;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #1b3d49;
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

.login-link {
    text-decoration: none;
    color:#007bff;
}
</style>
