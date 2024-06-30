<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];
$product_id = $_POST['product_id'];
$order_id = $_POST['order_id'];
$reason = $_POST['reason'];

$query = "INSERT INTO refunds (user_id, product_id, order_id, reason, status) VALUES (?, ?, ?, ?, 'pending')";
$stmt = $conn->prepare($query);
$stmt->bind_param('iiis', $user_id, $product_id, $order_id, $reason);

if ($stmt->execute()) {
    header('Location: Order.php');
} else {
    echo "Error: " . $stmt->error;
}
?>
