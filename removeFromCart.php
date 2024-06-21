<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$product_id = $input['product_id'] ?? null;

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Product ID not provided']);
    exit;
}

$user_id = $_SESSION['id'];

$stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Product removed from cart successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to remove product from cart']);
}
?>
