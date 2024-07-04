<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Update the order status to 'Cancel Order'
    $stmt = $conn->prepare("UPDATE orders SET status = 'Cancel Order' WHERE id = ?");
    $stmt->bind_param('i', $order_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Order cancelled successfully.";
    } else {
        $_SESSION['message'] = "Failed to cancel the order. Please try again.";
    }

    $stmt->close();
}

header('Location: Order.php');
exit;
?>
