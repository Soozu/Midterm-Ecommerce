<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Process the comment form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['id'];
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $query = "INSERT INTO ratings (user_id, product_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiis', $user_id, $product_id, $rating, $comment);

    if ($stmt->execute()) {
        header("Location: product.php?id=$product_id");
    } else {
        echo "Failed to submit your review. Please try again.";
    }
}
?>
