<?php
session_start();
include 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];
    $status = 'active';

    // Insert new product
    $query = "INSERT INTO products (name, description, price, stock_quantity, category_id, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssdiis', $name, $description, $price, $stock_quantity, $category_id, $status);
    $stmt->execute();

    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Product</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="container">
        <h1>Add New Product</h1>
        <form action="add_product.php" method="POST">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Product Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Product Price:</label>
                <input type="number" step="0.01" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="stock_quantity">Stock Quantity:</label>
                <input type="number" id="stock_quantity" name="stock_quantity" required>
            </div>
            <div class="form-group">
                <label for="category_id">Category ID:</label>
                <input type="number" id="category_id" name="category_id" required>
            </div>
            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
