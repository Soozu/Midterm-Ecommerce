<?php
session_start();
include 'db.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Check if the product ID and quantity are provided
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Update the product's stock quantity
    $query = "UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $quantity, $product_id);

    if ($stmt->execute()) {
        header('Location: ProductManagement.php');
        exit;
    } else {
        echo "Failed to add stock.";
    }
} else {
    echo "Product ID and quantity are required.";
}
?>
