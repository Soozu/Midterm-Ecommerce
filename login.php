<?php
session_start();
include 'db.php';

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Validate inputs
    if (empty($username) || empty($password)) {
        $error = "Please enter username and password.";
    } else {
        // Check the database for user
        $query = "SELECT id, username, password, role, status FROM users WHERE username = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $username, $hashed_password, $role, $status);
                $stmt->fetch();

                // Check if the account is active
                if ($status !== 'active') {
                    $error = "Your account is not active. Please contact support.";
                } else if (password_verify($password, $hashed_password)) {
                    // Password is correct, update last login, start a new session
                    $update_stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                    $update_stmt->bind_param("i", $id);
                    $update_stmt->execute();

                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $id;
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;
                    
                    // Redirect user based on role
                    if ($role === 'admin') {
                        header("Location: admin.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit();
                } else {
                    // Display an error message if password is not valid
                    $error = "The password you entered was not valid.";
                }
            } else {
                // Display an error message if username doesn't exist
                $error = "No account found with that username.";
            }
            $stmt->close();
        }
    }
}

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-commerce Mabines</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }
        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: #fff;
            color: #000;
            padding: 10px 0;
        }
        .login-container {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .login-container label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: #444;
        }
        .login-container .error, .login-container .warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .login-container p {
            margin-top: 10px;
        }
        .login-container a {
            color: #fff;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php
        // Display password changed notification
        if (isset($_SESSION['password_changed'])) {
            echo "<p class='success'>" . $_SESSION['password_changed'] . "</p>";
            unset($_SESSION['password_changed']); // Clear the message after displaying it
        }

        // Display errors or other messages
        if (isset($error)) {
            echo "<div class='error'>$error</div>";
        }
        ?>
        <form method="post" action="login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p><a href="forgot-password.php">Forgot Password?</a></p>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>
