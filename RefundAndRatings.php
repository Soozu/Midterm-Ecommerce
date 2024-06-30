<?php
session_start();
include 'db.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch refunds and ratings data
$refunds_query = "
    SELECT refunds.*, users.username, products.name AS product_name
    FROM refunds
    JOIN users ON refunds.user_id = users.id
    JOIN products ON refunds.product_id = products.id
";
$refunds_result = $conn->query($refunds_query);

$ratings_query = "
    SELECT ratings.*, users.username, products.name AS product_name
    FROM ratings
    JOIN users ON ratings.user_id = users.id
    JOIN products ON ratings.product_id = products.id
";
$ratings_result = $conn->query($ratings_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refunds and Ratings</title>
    <link rel="stylesheet" href="css/RefundAndRatings.css">
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
            <h1>Refunds and Ratings</h1>
            <p>Manage refunds and ratings here.</p>
        </div>

        <!-- Refunds Section -->
        <div class="admin-section">
            <h2>Refunds</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Product</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $refunds_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= htmlspecialchars($row['reason']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <form action="updateRefundStatus.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <select name="status">
                                    <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="approved" <?= $row['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                    <option value="rejected" <?= $row['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>
                                <button type="submit" class="admin-button">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Ratings Section -->
        <div class="admin-section">
            <h2>Ratings</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Product</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $ratings_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= htmlspecialchars($row['rating']) ?></td>
                        <td><?= htmlspecialchars($row['comment']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
