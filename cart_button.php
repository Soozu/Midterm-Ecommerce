<?php
session_start();
// Assuming you have a function to validate and sanitize input
$product_id = $_POST['product_id'] ?? null;

if ($product_id) {
    // Add to cart logic
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Check if product already in cart and increase quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    
    // Redirect to cart page to view added item
    header("Location: addToCart.php");
    exit;
}
?>
