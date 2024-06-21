<?php
session_start();
include 'db.php';
include 'header.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.22/css/uikit.min.css" />
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .uk-card {
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .uk-card-title {
            font-size: 1.5em;
            margin-bottom: 20px;
            color: #007bff;
        }
        .uk-card-body {
            font-size: 1em;
            color: #495057;
        }
        .uk-button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            display: inline-block;
            margin-top: 20px;
        }
        .uk-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="uk-card uk-card-default uk-card-body">
        <h2 class="uk-card-title">Order Successful!</h2>
        <p class="uk-card-body">Thank you for your purchase. Your order has been placed successfully. We will process it shortly and notify you once it's shipped.</p>
        <a href="index.php" class="uk-button">Back to Home</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.22/js/uikit.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.22/js/uikit-icons.min.js"></script>
</body>
</html>
