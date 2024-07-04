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

// Insert order
$query = "INSERT INTO orders (user_id, total, status, created_at, updated_at, shipping_address, shipping_city, shipping_postal_code, shipping_country)
          VALUES (?, ?, 'pending', NOW(), NOW(), ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('idssss', $user_id, $total_price, $shipping_address, $shipping_city, $shipping_postal_code, $shipping_country);
$stmt->execute();
$order_id = $stmt->insert_id;

// Insert order items
foreach ($cart_items as $product_id => $quantity) {
    $query = "INSERT INTO order_items (order_id, product_id, quantity, price)
              SELECT ?, id, ?, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iii', $order_id, $quantity, $product_id);
    $stmt->execute();
}

// Update cart items status
$query = "UPDATE cart_items SET status = 'ordered' WHERE user_id = ? AND status = 'active'";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();

header('Location: checkout.php');
exit;
?>
