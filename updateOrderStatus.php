<?php
session_start();
include 'db.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Update the order status in the database
    $update_query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('si', $status, $order_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Order status updated successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to update order status.";
    }

    header('Location: OrderManagement.php');
    exit;
} else {
    header('Location: OrderManagement.php');
    exit;
}
