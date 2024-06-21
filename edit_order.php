<?php
session_start();
include 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Get order details
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$order_id = $_GET['id'];
$query = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: admin.php');
    exit;
}

$order = $result->fetch_assoc();

// Update order details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $status, $order_id);
    $stmt->execute();
    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Order</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="container">
        <h1>Edit Order</h1>
        <form action="edit_order.php?id=<?= $order['id']; ?>" method="POST">
            <div class="form-group">
                <label for="status">Order Status:</label>
                <select id="status" name="status">
                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                    <option value="in_transit" <?= $order['status'] == 'in_transit' ? 'selected' : ''; ?>>In Transit</option>
                    <option value="delivered" <?= $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                </select>
            </div>
            <button type="submit">Update Order</button>
        </form>
    </div>
</body>
</html>
