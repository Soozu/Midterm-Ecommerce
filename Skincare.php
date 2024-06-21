<?php
session_start();
include 'db.php';  // Ensure this path is correct

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $_SESSION['login_required'] = true;  // Set a session variable
    header("Location: login.php");  // Redirect to the login page
    exit;
}

// Fetch makeup products from the database
$query = "SELECT * FROM products WHERE category_id = 1";  // Assuming '1' is the ID for Makeup
$result = $conn->query($query);

include 'header.php';  // Adjust the path as needed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skincare Products - Coming Soon</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Assuming you have a styles.css file -->
    <style>
        /* Additional styling for the coming soon page */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5; /* Light background color */
            font-family: Arial, sans-serif;
        }

        .coming-soon-container {
            text-align: center;
            background-color: #fff;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .coming-soon-container h1 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 20px;
        }

        .coming-soon-container p {
            font-size: 1.2em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="coming-soon-container">
        <h1>Skincare Products</h1>
        <p>Coming Soon...</p>
    </div>
</body>
</html>

