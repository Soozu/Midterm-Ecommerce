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
$query = "SELECT orders.id, orders.total, orders.status, orders.created_at, products.name, order_items.quantity 
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
    <link rel="stylesheet" href="order.css">
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    margin: 170px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

.order-section {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.order-item {
    width: 48%;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.order-item h3 {
    margin-bottom: 10px;
    color: #007BFF;
}

.order-item p {
    margin: 5px 0;
    color: #555;
}

.order-item p.status {
    font-weight: bold;
    color: #333;
}

@media (max-width: 768px) {
    .order-item {
        width: 100%;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h1>My Orders</h1>
        <section class="order-section">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($order = $result->fetch_assoc()): ?>
                    <div class="order-item">
                        <h3>Order ID: <?= htmlspecialchars($order['id']); ?></h3>
                        <p>Product: <?= htmlspecialchars($order['name']); ?></p>
                        <p>Quantity: <?= htmlspecialchars($order['quantity']); ?></p>
                        <p>Total: $<?= number_format($order['total'], 2); ?></p>
                        <p>Status: <?= $order_status_messages[$order['status']]; ?></p>
                        <p>Ordered on: <?= htmlspecialchars($order['created_at']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No orders found.</p>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
