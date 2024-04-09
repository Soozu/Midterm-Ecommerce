<?php
session_start();

// Check for the session variable and display it
if (isset($_SESSION['product_added'])) {
    echo "<div class='success-message'>" . htmlspecialchars($_SESSION['product_added']) . "</div>";
    // Unset the session variable so the message doesn't persist on page refresh
    unset($_SESSION['product_added']);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Add Product</title>
    <link rel="stylesheet" href="css/admin.css"> 
</head>
<body>
<div class="admin-container">
    <div class="admin-header">
        <h1>Add New Product</h1>
    </div>
    <form class="admin-form" action="product_process.php" method="post" enctype="multipart/form-data">
        <label for="product-name">Product Name:</label>
        <input type="text" id="product-name" name="product_name" required>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" required step="0.01">

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4"></textarea>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" required>

        <button type="submit" name="submit">Add Product</button>
    </form>
</div>
</body>
</html>
