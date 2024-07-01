<?php
session_start();
include 'db.php';
include 'header.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch orders for the logged-in user that are not delivered
$user_id = $_SESSION['id'];
$query = "SELECT orders.id, orders.total, orders.status, orders.created_at, products.id AS product_id, products.name, order_items.quantity 
          FROM orders 
          INNER JOIN order_items ON orders.id = order_items.order_id 
          INNER JOIN products ON order_items.product_id = products.id 
          WHERE orders.user_id = ? AND orders.status != 'delivered'";
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

// Fetch delivered orders for the logged-in user
$delivered_query = "SELECT delivered_orders.id, delivered_orders.created_at AS delivered_at, products.id AS product_id, products.name, delivered_order_items.quantity, delivered_order_items.price 
                    FROM delivered_orders 
                    INNER JOIN delivered_order_items ON delivered_orders.id = delivered_order_items.order_id 
                    INNER JOIN products ON delivered_order_items.product_id = products.id 
                    WHERE delivered_orders.user_id = ?";
$delivered_stmt = $conn->prepare($delivered_query);
$delivered_stmt->bind_param('i', $user_id);
$delivered_stmt->execute();
$delivered_result = $delivered_stmt->get_result();
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
                                    <a href="requestRefund.php?order_id=<?= htmlspecialchars($order['id']); ?>&product_id=<?= htmlspecialchars($order['product_id']); ?>" class="btn">Refund</a>
                                    <a href="rateProduct.php?order_id=<?= htmlspecialchars($order['id']); ?>&product_id=<?= htmlspecialchars($order['product_id']); ?>" class="btn">Rate</a>
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

    <h1>Delivered Orders</h1>
    <div class="orders-list">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Delivered On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($delivered_result->num_rows > 0): ?>
                    <?php while ($delivered = $delivered_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($delivered['id']); ?></td>
                            <td><?= htmlspecialchars($delivered['name']); ?></td>
                            <td><?= htmlspecialchars($delivered['quantity']); ?></td>
                            <td>₱<?= number_format($delivered['price'], 2); ?></td>
                            <td>Your item is delivered</td>
                            <td><?= htmlspecialchars($delivered['delivered_at']); ?></td>
                            <td>
                                <a href="requestRefund.php?order_id=<?= htmlspecialchars($delivered['id']); ?>&product_id=<?= htmlspecialchars($delivered['product_id']); ?>" class="btn">Refund</a>
                                <a href="rateProduct.php?order_id=<?= htmlspecialchars($delivered['id']); ?>&product_id=<?= htmlspecialchars($delivered['product_id']); ?>" class="btn">Rate</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7">No delivered orders found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
