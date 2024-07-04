<?php
session_start();
include 'db.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

function updateOrderStatus($conn, $orderId, $status) {
    $conn->begin_transaction();

    try {
        // Update the order status
        $query = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('si', $status, $orderId);
        $stmt->execute();

        // Check if the order is delivered
        if ($status === 'delivered') {
            // Insert the delivered order into the delivered_orders table
            $query = "INSERT INTO delivered_orders (user_id, total, status, created_at, updated_at, shipping_address, shipping_city, shipping_postal_code, shipping_country)
                      SELECT user_id, total, 'delivered', created_at, updated_at, shipping_address, shipping_city, shipping_postal_code, shipping_country
                      FROM orders WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $orderId);
            $stmt->execute();
            
            // Get the new delivered order id
            $deliveredOrderId = $stmt->insert_id;

            // Insert the delivered order items into the delivered_order_items table
            $query = "INSERT INTO delivered_order_items (order_id, product_id, quantity, price)
                      SELECT ?, product_id, quantity, price FROM order_items WHERE order_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $deliveredOrderId, $orderId);
            $stmt->execute();
        }

        $conn->commit();
        echo "Order status updated successfully.";
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        throw $exception;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $status = $_POST['status'];

    updateOrderStatus($conn, $orderId, $status);
}

// Fetch orders from the database
$query = "SELECT * FROM orders";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<div class="admin-container">
    <div class="admin-sidebar">
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="OrderManagement.php">Order Management</a></li>
            <li><a href="ProductManagement.php">Product Management</a></li>
            <li><a href="Categories.php">Categories</a></li>
            <li><a href="RefundAndRatings.php">Refund & Ratings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="admin-main">
        <div class="admin-header">
            <h1>Order Management</h1>
            <p>Manage your orders here.</p>
        </div>
        <div class="admin-orders">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['user_id']) ?></td>
                        <td>₱<?= number_format($row['total'], 2) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td><?= htmlspecialchars($row['updated_at']) ?></td>
                        <td>
                            <form action="OrderManagement.php" method="POST" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                <select name="status">
                                    <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="paid" <?= $row['status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                                    <option value="in_transit" <?= $row['status'] === 'in_transit' ? 'selected' : '' ?>>In Transit</option>
                                    <option value="shipped" <?= $row['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                    <option value="delivered" <?= $row['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
