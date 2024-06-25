<?php
session_start();
include 'db.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch all products from the database
$product_query = "SELECT products.*, categories.name AS category_name FROM products LEFT JOIN categories ON products.category_id = categories.id ORDER BY products.created_at DESC";
$product_result = $conn->query($product_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
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
                <h1>Product Management</h1>
                <a href="addProduct.php" class="admin-button">Add New Product</a>
            </header>
            <section class="admin-products">
                <?php if ($product_result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($product = $product_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['id']); ?></td>
                                    <td><?= htmlspecialchars($product['name']); ?></td>
                                    <td><?= htmlspecialchars($product['category_name']); ?></td>
                                    <td>₱<?= number_format($product['price'], 2); ?></td>
                                    <td><?= htmlspecialchars($product['stock_quantity']); ?></td>
                                    <td><?= htmlspecialchars($product['status']); ?></td>
                                    <td>
                                        <a href="editProduct.php?id=<?= $product['id']; ?>" class="admin-button">Edit</a>
                                        <form method="POST" action="deleteProduct.php" style="display:inline-block;">
                                            <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                            <button type="submit" class="admin-button-delete">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No products found.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>
