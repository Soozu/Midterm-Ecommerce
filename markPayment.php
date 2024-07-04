<?php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'];
$total = $data['total'];

$query = "UPDATE orders SET status = 'paid' WHERE user_id = ? AND total = ? AND status = 'pending'";
$stmt = $conn->prepare($query);
$stmt->bind_param('id', $user_id, $total);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to mark payment as done.']);
}
?>
