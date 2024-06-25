<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Validate the product_id
if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    echo "Invalid product ID.";
    exit;
}

$product_id = intval($_GET['product_id']);
$user_id = intval($_SESSION['id']);
$checkout = isset($_GET['checkout']) ? $_GET['checkout'] : false;

// Start transaction
$conn->begin_transaction();

try {
    // Check if the item is already in the cart
    $query = "SELECT quantity FROM cart_items WHERE user_id = ? AND product_id = ? AND status = 'active'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the item is already in the cart, increase its quantity
        $query = "UPDATE cart_items SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
    } else {
        // If the item is not in the cart, insert it
        $query = "INSERT INTO cart_items (user_id, product_id, quantity, status) VALUES (?, ?, 1, 'active')";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();

    // Update the stock quantity
    $query = "UPDATE products SET stock_quantity = stock_quantity - 1 WHERE id = ? AND stock_quantity > 0";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    

    // Check if the stock update was successful
    if ($stmt->affected_rows === 0) {
        throw new Exception("Failed to update stock quantity. Product may be out of stock.");
    }

    // Commit transaction
    $conn->commit();

    // Set session variable for direct checkout
    if ($checkout) {
        $_SESSION['success'] = "Item added to cart. Redirecting to checkout...";
        $_SESSION['checkout_product_id'] = $product_id;
        header("Location: checkout.php");
    } else {
        $_SESSION['success'] = "Item added to cart.";
        header("Location: viewCart.php");
    }
    
    exit;

} catch (Exception $e) {
    // Rollback transaction in case of error
    $conn->rollback();
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: viewCart.php");
    exit;
}
?>
