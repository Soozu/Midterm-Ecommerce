<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    if (empty($username) || empty($password) || empty($email)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $query = "SELECT id FROM users WHERE username = ? OR email = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Username or email already taken.";
            } else {
                $query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
                if ($stmt = $conn->prepare($query)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt->bind_param("sss", $username, $hashed_password, $email);
                    if ($stmt->execute()) {
                        $success = "Registration successful. You can now <a href='login.php'>login</a>.";
                    } else {
                        $error = "Error: " . $stmt->error;
                    }
                }
            }
            $stmt->close();
        }
    }
}
?>
<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - E-commerce Mabines</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: #fff;
            color: #000;
            padding: 10px 0;
            z-index: 1000;
        }
        .register-container {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
            margin-top: 100px;
        }
        .register-container h2 {
            margin-bottom: 20px;
        }
        .register-container label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
        }
        .register-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
        }
        .register-container button {
            width: 100%;
            padding: 10px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .register-container button:hover {
            background-color: #444;
        }
        .register-container .error, .register-container .success {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .register-container .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <header>
        <h1>E-commerce Mabines</h1>
    </header>
    <div class="register-container">
        <h2>Register</h2>
        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        <?php if (isset($success)) echo "<div class='success'>$success</div>"; ?>
        <form action="register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
