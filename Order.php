<?php
session_start();
include 'db.php';
include 'header.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch orders for the logged-in user
$user_id = $_SESSION['id'];
$query = "SELECT orders.id, orders.total, orders.status, orders.created_at, products.name, order_items.quantity, order_items.product_id 
          FROM orders 
          INNER JOIN order_items ON orders.id = order_items.order_id 
          INNER JOIN products ON order_items.product_id = products.id 
          WHERE orders.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$order_status_messages = [
    'pending' => 'Pending',
    'shipped' => 'Your item is packed and waiting for delivery',
    'in_transit' => 'Your item is with our logistic partner',
    'delivered' => 'Your item is delivered'
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Tracking</title>
    <link rel="stylesheet" href="css/order.css">
</head>
<body>
<div class="orders-container">
    <h1>Your Orders</h1>
    <div class="orders-list">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ordered On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($order = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']); ?></td>
                            <td><?= htmlspecialchars($order['name']); ?></td>
                            <td><?= htmlspecialchars($order['quantity']); ?></td>
                            <td>₱<?= number_format($order['total'], 2); ?></td>
                            <td><?= $order_status_messages[$order['status']]; ?></td>
                            <td><?= htmlspecialchars($order['created_at']); ?></td>
                            <td>
                                <?php if ($order['status'] == 'delivered'): ?>
                                    <a href="requestRefund.php?order_id=<?= $order['id']; ?>&product_id=<?= $order['product_id']; ?>">Refund</a>
                                    <a href="rateProduct.php?order_id=<?= $order['id']; ?>&product_id=<?= $order['product_id']; ?>">Rate</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7">No orders found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
