<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];
$order_id = $_GET['order_id'];
$product_id = $_GET['product_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reason = $_POST['reason'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert refund request
        $stmt = $conn->prepare("INSERT INTO refunds (user_id, product_id, order_id, reason, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("iiis", $user_id, $product_id, $order_id, $reason);
        if ($stmt->execute()) {
            // Update order status to "refund item"
            $update_stmt = $conn->prepare("UPDATE orders SET status = 'refund item' WHERE id = ?");
            $update_stmt->bind_param("i", $order_id);
            if ($update_stmt->execute()) {
                // Commit the transaction
                $conn->commit();
                header('Location: Order.php');
                exit;
            } else {
                echo "Error updating order status: " . $update_stmt->error;
                $conn->rollback();
            }
        } else {
            echo "Error inserting refund request: " . $stmt->error;
            $conn->rollback();
        }
    } catch (Exception $e) {
        // Rollback the transaction if any error occurs
        $conn->rollback();
        echo "Exception: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Refund</title>
    <link rel="stylesheet" href="css/refundRating.css">
</head>
<body>
<div class="form-container">
    <h2>Request Refund</h2>
    <form action="requestRefund.php?order_id=<?= $order_id ?>&product_id=<?= $product_id ?>" method="POST">
        <label for="reason">Reason for refund:</label>
        <textarea id="reason" name="reason" rows="4" required></textarea>
        <button type="submit">Submit Refund Request</button>
    </form>
</div>
</body>
</html>
