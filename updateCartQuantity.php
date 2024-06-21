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
$new_quantity = $input['quantity'] ?? 0;

if (!$product_id || $new_quantity < 1) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID or quantity']);
    exit;
}

// Check the current stock quantity
$stmt = $conn->prepare("SELECT stock_quantity FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_stock = $row['stock_quantity'];

    // Check if the requested quantity is available
    if ($new_quantity > $current_stock) {
        echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

// Proceed to update the cart if there's enough stock
$stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE product_id = ? AND user_id = ?");
$stmt->bind_param("iii", $new_quantity, $product_id, $_SESSION['id']);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Quantity updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update quantity']);
}
?>
