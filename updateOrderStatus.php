<?php
session_start();
include 'db.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Validate the order ID and new status
if (!isset($_POST['order_id']) || !is_numeric($_POST['order_id']) || !isset($_POST['status'])) {
    echo "Invalid order ID or status.";
    exit;
}

$order_id = intval($_POST['order_id']);
$status = $_POST['status'];

// Start transaction
$conn->begin_transaction();

try {
    // Update order status
    $query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $status, $order_id);
    $stmt->execute();

    // If status is delivered, move the order to the delivered_orders table
    if ($status === 'delivered') {
        // Get the order details
        $query = "SELECT * FROM orders WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        $order_result = $stmt->get_result();
        $order = $order_result->fetch_assoc();

        // Insert into delivered_orders table
        $query = "INSERT INTO delivered_orders (id, user_id, total, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iissss', $order['id'], $order['user_id'], $order['total'], $status, $order['created_at'], $order['updated_at']);
        $stmt->execute();

        // Get the order items
        $query = "SELECT * FROM order_items WHERE order_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        $order_items_result = $stmt->get_result();

        // Insert order items into a new table (e.g., delivered_order_items) or update their status
        while ($order_item = $order_items_result->fetch_assoc()) {
            $query = "INSERT INTO delivered_order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iiid', $order_item['order_id'], $order_item['product_id'], $order_item['quantity'], $order_item['price']);
            $stmt->execute();
        }

        // Delete order items from the order_items table
        $query = "DELETE FROM order_items WHERE order_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $order_id);
        $stmt->execute();

        // Delete from orders table
        $query = "DELETE FROM orders WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
    }

    // Commit transaction
    $conn->commit();

    echo "Order status updated successfully.";
} catch (Exception $e) {
    // Rollback transaction in case of error
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}
?>
