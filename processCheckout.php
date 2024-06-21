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

$conn->begin_transaction();

try {
    // Insert into orders table
    $query = "INSERT INTO orders (user_id, total, status, created_at, updated_at) VALUES (?, ?, 'pending', NOW(), NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('id', $user_id, $total_price);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert into order_shipping table
    $query = "INSERT INTO order_shipping (order_id, address, city, postal_code, country) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('issss', $order_id, $shipping_address, $shipping_city, $shipping_postal_code, $shipping_country);
    $stmt->execute();

    // Insert into order_items table and update cart_items status
    $query = "SELECT cart_items.*, products.price AS product_price FROM cart_items INNER JOIN products ON cart_items.product_id = products.id WHERE cart_items.user_id = ? AND cart_items.status = 'active'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($item = $result->fetch_assoc()) {
        $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iiid', $order_id, $item['product_id'], $item['quantity'], $item['product_price']);
        $stmt->execute();

        // Update cart_items status to 'purchased'
        $query = "UPDATE cart_items SET status = 'purchased' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $item['id']);
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
