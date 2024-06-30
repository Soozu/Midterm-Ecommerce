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
    <link rel="stylesheet" href="css/ProductManagement.css">
</head>
<body>
<div class="admin-container">
    <div class="admin-sidebar">
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="OrderManagement.php">Order Management</a></li>
            <li><a href="ProductManagement.php">Product Management</a></li>
            <li><a href="Categories.php">Categories</a></li>
        </ul>
    </div>
    <div class="admin-main">
        <div class="admin-header">
            <h1>Product Management</h1>
            <p>Manage your products here.</p>
        </div>
        <div class="admin-button">
            <button onclick="openPopup()">Add Product</button>
        </div>
        <div class="admin-orders" style="overflow-y: auto; height: 400px;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Image</th>
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
                        <td><img src="img/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" width="50"></td>
                        <td>
                            <a href="editProduct.php?id=<?= $row['id'] ?>" class="admin-button">Edit</a>
                            <form action="deleteProduct.php" method="POST" style="display:inline;" class="delete-product-form">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" class="admin-button-delete">Delete</button>
                            </form>
                            <button class="admin-button-add-stock" onclick="openAddStockPopup(<?= $row['id'] ?>)">Add Stock</button>
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

        <label for="image">Product Image</label>
        <input type="file" id="image" name="image" accept="image/*">

        <button type="submit">Add Product</button>
        <button type="button" onclick="closePopup()">Cancel</button>
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
        <button type="submit">Add Stock</button>
        <button type="button" onclick="closeAddStockPopup()">Cancel</button>
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
