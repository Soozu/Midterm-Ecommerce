<?php
session_start();
include 'db.php';  // Ensure this path is correct

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Product ID not provided']);
    exit;
}

$product_id = $input['product_id'];
$user_id = $_SESSION['id'];

$stmt = $conn->prepare("DELETE FROM user_favorites WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
if ($stmt->execute()) {
    // Check if any rows were actually affected
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Removed from favorites successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No favorites were removed']);
    }
} else {
    // Provide error details from the database connection
    echo json_encode(['success' => false, 'message' => 'Failed to remove from favorites: ' . $stmt->error]);
}
?>
