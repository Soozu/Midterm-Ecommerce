<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];
$order_id = $_GET['order_id'];
$product_id = $_GET['product_id'];

$query = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "Order not found.";
    exit;
}

$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Refund</title>
    <link rel="stylesheet" href="css/requestRefund.css">
</head>
<body>
<div class="request-refund-container">
    <h1>Request Refund for: <?= htmlspecialchars($product['name']); ?></h1>
    <form action="submitRefund.php" method="POST">
        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id); ?>">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id); ?>">
        <label for="reason">Reason for Refund:</label>
        <textarea id="reason" name="reason" rows="4" required></textarea>
        <button type="submit">Submit Refund Request</button>
    </form>
</div>
</body>
</html>