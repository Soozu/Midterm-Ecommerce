<?php
session_start();
include 'db.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch all orders from the database
$order_query = "SELECT orders.*, users.username, users.email FROM orders INNER JOIN users ON orders.user_id = users.id ORDER BY orders.created_at DESC";
$order_result = $conn->query($order_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Management</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="admin-container">
        <nav class="admin-sidebar">
            <ul>
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="OrderManagement.php">Order Management</a></li>
                <li><a href="ProductManagement.php">Product Management</a></li>
                <li><a href="Categories.php">Categories</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <main class="admin-main">
            <header class="admin-header">
                <h1>Order Management</h1>
            </header>
            <section class="admin-orders">
                <?php if ($order_result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $order_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['id']); ?></td>
                                    <td><?= htmlspecialchars($order['username']); ?></td>
                                    <td><?= htmlspecialchars($order['email']); ?></td>
                                    <td>₱<?= number_format($order['total'], 2); ?></td>
                                    <td><?= htmlspecialchars($order['status']); ?></td>
                                    <td><?= htmlspecialchars($order['created_at']); ?></td>
                                    <td>
                                        <form method="POST" action="updateOrderStatus.php">
                                            <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                            <select name="status">
                                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                                <option value="in_transit" <?= $order['status'] === 'in_transit' ? 'selected' : ''; ?>>In Transit</option>
                                                <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                            </select>
                                            <button type="submit">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No orders found.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>
