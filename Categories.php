<?php
session_start();
include 'db.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch all categories from the database
$category_query = "SELECT * FROM categories ORDER BY name ASC";
$category_result = $conn->query($category_query);

// Handle add category form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];

    // Insert new category into the database
    $insert_query = "INSERT INTO categories (name) VALUES (?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param('s', $category_name);

    if ($stmt->execute()) {
        header('Location: Categories.php');
        exit;
    } else {
        $error_message = "Error adding category. Please try again.";
    }
}

// Handle delete category form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    $category_id = $_POST['category_id'];

    // Delete category from the database
    $delete_query = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param('i', $category_id);

    if ($stmt->execute()) {
        header('Location: Categories.php');
        exit;
    } else {
        $error_message = "Error deleting category. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Category Management</title>
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
                <h1>Category Management</h1>
            </header>
            <section class="admin-categories">
                <h2>Existing Categories</h2>
                <?php if ($category_result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Category ID</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($category = $category_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($category['id']); ?></td>
                                    <td><?= htmlspecialchars($category['name']); ?></td>
                                    <td>
                                        <form method="POST" action="" style="display:inline-block;">
                                            <input type="hidden" name="category_id" value="<?= $category['id']; ?>">
                                            <button type="submit" name="delete_category" class="admin-button-delete">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No categories found.</p>
                <?php endif; ?>
            </section>
            <section class="admin-form">
                <h2>Add New Category</h2>
                <form method="POST" action="">
                    <label for="category_name">Category Name:</label>
                    <input type="text" id="category_name" name="category_name" required>
                    <button type="submit" name="add_category">Add Category</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
