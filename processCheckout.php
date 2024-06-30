<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];
$shipping_address = $_POST['shipping_address'];
$shipping_city = $_POST['shipping_city'];
$shipping_postal_code = $_POST['shipping_postal_code'];
$shipping_country = $_POST['shipping_country'];
$total_price = $_POST['total_price'];
$cart_items = $_POST['cart_items'];

$conn->begin_transaction();

try {
    // Insert into orders table with shipping details
    $query = "INSERT INTO orders (user_id, total, status, created_at, updated_at, shipping_address, shipping_city, shipping_postal_code, shipping_country) 
              VALUES (?, ?, 'pending', NOW(), NOW(), ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('idssss', $user_id, $total_price, $shipping_address, $shipping_city, $shipping_postal_code, $shipping_country);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert into order_items table and update cart_items status
    foreach ($cart_items as $product_id => $quantity) {
        $query = "SELECT price FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iiid', $order_id, $product_id, $quantity, $product['price']);
        $stmt->execute();

        // Update cart_items status to 'purchased'
        $query = "UPDATE cart_items SET status = 'purchased' WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $user_id, $product_id);
        $stmt->execute();
    }

    $conn->commit();

    // Redirect to order success page
    header('Location: orderSuccess.php');
    exit;
} catch (mysqli_sql_exception $exception) {
    $conn->rollback();
    throw $exception;
}
?>
