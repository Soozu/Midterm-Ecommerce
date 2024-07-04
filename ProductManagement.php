<?php
session_start();
include 'db.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch products from the database
$query = "SELECT * FROM products";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <style>
/* Global Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.admin-container {
    display: flex;
    height: 100vh;
}

.admin-sidebar {
    width: 500px;
    background-color: #333;
    color: #fff;
}

.admin-sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.admin-sidebar ul li {
    padding: 20px;
}

.admin-sidebar ul li a {
    color: #fff;
    text-decoration: none;
}

.admin-sidebar ul li a:hover {
    background-color: #555;
}

.admin-main {
    flex-grow: 1;
    padding: 20px;
}

.admin-header {
    margin-bottom: 20px;
}

/* Table and Orders Styling */
.admin-orders {
    max-height: 400px;
    overflow-y: auto;
}

.admin-orders table {
    width: 100%;
    border-collapse: collapse;
}

.admin-orders th, .admin-orders td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

.admin-orders th {
    background-color: #333;
    color: white;
}

.btn {
    padding: 10px 16px; /* Adjusted padding for better click area */
    border: none;
    cursor: pointer;
    font-size: 14px;
    border-radius: 4px;
    margin-right: 10px;
    transition: opacity 0.3s ease; /* Smooth transition for opacity */
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-edit {
    background-color: #28a745;
}

.btn-delete {
    background-color: #dc3545;
}

.btn-add-stock {
    background-color: #ffc107;
    color: #333;
}

.btn:hover {
    opacity: 0.8;
}

.action-btn {
    margin-bottom: 5px; /* Space between action buttons */
}


/* Popup Styles */
.popup-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

.popup {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    width: 90%;
    max-width: 500px;
}

.popup.active, .popup-overlay.active {
    display: block;
}

.popup h2 {
    margin-top: 0;
}

.popup form {
    display: flex;
    flex-direction: column;
}

.popup form label {
    margin-bottom: 5px;
}

.popup form input, .popup form select, .popup form textarea {
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.popup form button {
    padding: 10px;
    border: none;
    cursor: pointer;
    background-color: #007bff;
    color: white;
    margin-top: 10px;
}

.popup form button[type="button"] {
    background-color: #6c757d;
}

.popup form button:hover {
    opacity: 0.8;
}
</style>
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
            <h1>Product Management</h1>
            <p>Manage your products here.</p>
        </div>
        <div class="admin-button">
            <button class="btn btn-primary" onclick="openPopup()">Add Product</button>
        </div>
        <div class="admin-orders">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>₱<?= number_format($row['price'], 2) ?></td>
                        <td><?= htmlspecialchars($row['category_id']) ?></td>
                        <td><?= htmlspecialchars($row['stock_quantity']) ?></td>
                        <td class="action-buttons">
                            <div class="action-btn">
                                <a href="editProduct.php?id=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>
                            </div>
                            <div class="action-btn">
                                <form action="deleteProduct.php" method="POST" class="delete-product-form">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-delete">Delete</button>
                                </form>
                            </div>
                            <div class="action-btn">
                                <button class="btn btn-add-stock" onclick="openAddStockPopup(<?= $row['id'] ?>)">Add Stock</button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pop-up Add Product Form -->
<div class="popup-overlay" id="popupOverlay"></div>
<div class="popup" id="popup">
    <h2>Add Product</h2>
    <form action="addProduct.php" method="POST" enctype="multipart/form-data">
        <label for="name">Product Name</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <label for="price">Price</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="category_id">Category</label>
        <select id="category_id" name="category_id" required>
            <!-- Populate categories dynamically -->
            <?php
            $cat_query = "SELECT * FROM categories";
            $cat_result = $conn->query($cat_query);
            while ($cat_row = $cat_result->fetch_assoc()) {
                echo "<option value='{$cat_row['id']}'>{$cat_row['name']}</option>";
            }
            ?>
        </select>

        <label for="stock_quantity">Stock Quantity</label>
        <input type="number" id="stock_quantity" name="stock_quantity" required>

        <button type="submit" class="btn btn-primary">Add Product</button>
        <button type="button" onclick="closePopup()" class="btn btn-secondary">Cancel</button>
    </form>
</div>

<!-- Pop-up Add Stock Form -->
<div class="popup-overlay" id="addStockPopupOverlay"></div>
<div class="popup" id="addStockPopup">
    <h2>Add Stock</h2>
    <form action="addStock.php" method="POST">
        <input type="hidden" id="add_stock_product_id" name="product_id">
        <label for="add_stock_quantity">Stock Quantity</label>
        <input type="number" id="add_stock_quantity" name="quantity" required>
        <button type="submit" class="btn btn-primary">Add Stock</button>
        <button type="button" onclick="closeAddStockPopup()" class="btn btn-secondary">Cancel</button>
    </form>
</div>

<script>
function openPopup() {
    document.getElementById('popup').classList.add('active');
    document.getElementById('popupOverlay').classList.add('active');
}

function closePopup() {
    document.getElementById('popup').classList.remove('active');
    document.getElementById('popupOverlay').classList.remove('active');
}

function openAddStockPopup(productId) {
    document.getElementById('add_stock_product_id').value = productId;
    document.getElementById('addStockPopup').classList.add('active');
    document.getElementById('addStockPopupOverlay').classList.add('active');
}

function closeAddStockPopup() {
    document.getElementById('addStockPopup').classList.remove('active');
    document.getElementById('addStockPopupOverlay').classList.remove('active');
}

document.querySelectorAll('.delete-product-form').forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to delete this product?')) {
            fetch('deleteProduct.php', {
                method: 'POST',
                body: new FormData(this)
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product deleted successfully');
                    location.reload();
                } else {
                    alert('Failed to delete product: ' + data.message);
                }
            });
        }
    });
});
</script>
</body>
</html>