<?php
session_start();
include 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Delete order if requested
if (isset($_GET['delete_order']) && is_numeric($_GET['delete_order'])) {
    $order_id = $_GET['delete_order'];

    // Delete order items associated with this order
    $query = "DELETE FROM order_items WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $order_id);
    $stmt->execute();

    // Delete the order
    $query = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    
    header('Location: admin.php');
    exit;
}

// Delete product if requested
if (isset($_GET['delete_product']) && is_numeric($_GET['delete_product'])) {
    $product_id = $_GET['delete_product'];

    // Delete the product
    $query = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    
    header('Location: admin.php');
    exit;
}

// Fetch all orders
$query = "SELECT orders.id, users.username, orders.total, orders.status, orders.created_at 
          FROM orders 
          INNER JOIN users ON orders.user_id = users.id";
$orders_result = $conn->query($query);

// Fetch all products
$query = "SELECT * FROM products";
$products_result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Orders and Products</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <nav class="admin-nav">
        <ul>
            <li><a href="index.php">Homepage</a></li>
            <li><a href="admin.php">Admin Dashboard</a></li>
            <li><a href="add_product.php">Add Product</a></li>
            <li><a href="logout.php">Log Out</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Manage Orders</h1>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Username</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ordered on</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orders_result->num_rows > 0): ?>
                    <?php while ($order = $orders_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']); ?></td>
                            <td><?= htmlspecialchars($order['username']); ?></td>
                            <td>$<?= number_format($order['total'], 2); ?></td>
                            <td><?= htmlspecialchars($order['status']); ?></td>
                            <td><?= htmlspecialchars($order['created_at']); ?></td>
                            <td>
                                <a href="edit_order.php?id=<?= $order['id']; ?>" class="button">Edit</a>
                                <a href="admin.php?delete_order=<?= $order['id']; ?>" class="button delete-button" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h1>Manage Products</h1>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock Quantity</th>
                    <th>Category ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products_result->num_rows > 0): ?>
                    <?php while ($product = $products_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['id']); ?></td>
                            <td><?= htmlspecialchars($product['name']); ?></td>
                            <td><?= htmlspecialchars($product['description']); ?></td>
                            <td>$<?= number_format($product['price'], 2); ?></td>
                            <td><?= htmlspecialchars($product['stock_quantity']); ?></td>
                            <td><?= htmlspecialchars($product['category_id']); ?></td>
                            <td>
                                <a href="edit_product.php?id=<?= $product['id']; ?>" class="button">Edit</a>
                                <a href="admin.php?delete_product=<?= $product['id']; ?>" class="button delete-button" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="add_product.php" class="button">Add New Product</a>
    </div>
</body>
</html>
