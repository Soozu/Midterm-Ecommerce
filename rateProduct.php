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
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("INSERT INTO ratings (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $product_id, $rating, $comment);
    if ($stmt->execute()) {
        header('Location: Order.php');
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rate Product</title>
    <link rel="stylesheet" href="css/refundRating.css">
</head>
<body>
<div class="form-container">
    <h2>Rate Product</h2>
    <form action="rateProduct.php?order_id=<?= $order_id ?>&product_id=<?= $product_id ?>" method="POST">
        <label for="rating">Rating:</label>
        <select id="rating" name="rating" required>
            <option value="1">1 Star</option>
            <option value="2">2 Stars</option>
            <option value="3">3 Stars</option>
            <option value="4">4 Stars</option>
            <option value="5">5 Stars</option>
        </select>
        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment" rows="4" required></textarea>
        <button type="submit">Submit Rating</button>
    </form>
</div>
</body>
</html>
