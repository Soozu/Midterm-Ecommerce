<?php
session_start();
include 'db.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch product details
$product_id = $_GET['id'];
$product_query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($product_query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$product_result = $stmt->get_result();
$product = $product_result->fetch_assoc();

// Fetch categories for the dropdown
$category_query = "SELECT * FROM categories ORDER BY name ASC";
$category_result = $conn->query($category_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $stock_quantity = $_POST['stock_quantity'];
    $status = $_POST['status'];

    // Handle file upload
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    } else {
        $target_file = $product['image'];
    }

    // Update product in the database
    $update_query = "UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, image = ?, stock_quantity = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('ssdisisi', $name, $description, $price, $category_id, $target_file, $stock_quantity, $status, $product_id);

    if ($stmt->execute()) {
        header('Location: ProductManagement.php');
        exit;
    } else {
        $error_message = "Error updating product. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
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
                <h1>Edit Product</h1>
            </header>
            <section class="admin-form">
                <form method="POST" enctype="multipart/form-data">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>
                    
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?= htmlspecialchars($product['description']); ?></textarea>
                    
                    <label for="price">Price:</label>
                    <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($product['price']); ?>" required>
                    
                    <label for="category_id">Category:</label>
                    <select id="category_id" name="category_id" required>
                        <?php while ($category = $category_result->fetch_assoc()): ?>
                            <option value="<?= $category['id']; ?>" <?= $product['category_id'] == $category['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($category['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                    
                    <label for="image">Image:</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    
                    <label for="stock_quantity">Stock Quantity:</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" value="<?= htmlspecialchars($product['stock_quantity']); ?>" required>
                    
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="active" <?= $product['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?= $product['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                    
                    <button type="submit">Update Product</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
