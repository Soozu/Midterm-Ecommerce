<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];
$product_id = $_POST['product_id'];
$order_id = $_POST['order_id'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];

$product_query = "SELECT id FROM products WHERE id = ?";
$stmt = $conn->prepare($product_query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$product_result = $stmt->get_result();

if ($product_result->num_rows == 0) {
    echo "Error: Product ID does not exist.";
    exit;
}

$query = "INSERT INTO ratings (user_id, product_id, order_id, rating, comment) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('iiiis', $user_id, $product_id, $order_id, $rating, $comment);

if ($stmt->execute()) {
    header('Location: Order.php');
} else {
    echo "Error: " . $stmt->error;
}
?>
