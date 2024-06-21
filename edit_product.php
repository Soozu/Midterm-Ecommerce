<?php
session_start();
include 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Check if product ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid product ID.";
    exit;
}

$product_id = $_GET['id'];

// Fetch the product details
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit;
}

// Update product details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];

    $query = "UPDATE products SET name = ?, description = ?, price = ?, stock_quantity = ?, category_id = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssdiii', $name, $description, $price, $stock_quantity, $category_id, $product_id);
    $stmt->execute();

    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>
        <form method="POST">
            <div class="form-group">
                <label>Product ID:</label>
                <span><?= htmlspecialchars($product['id']); ?></span>
            </div>
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" required><?= htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Price:</label>
                <input type="number" name="price" value="<?= htmlspecialchars($product['price']); ?>" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Stock Quantity:</label>
                <input type="number" name="stock_quantity" value="<?= htmlspecialchars($product['stock_quantity']); ?>" required>
            </div>
            <div class="form-group">
                <label>Category ID:</label>
                <input type="number" name="category_id" value="<?= htmlspecialchars($product['category_id']); ?>" required>
            </div>
            <button type="submit" class="button">Update Product</button>
        </form>
    </div>
</body>
</html>
