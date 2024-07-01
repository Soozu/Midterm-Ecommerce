<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $refund_id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE refunds SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $refund_id);
    $stmt->execute();

    header('Location: RefundAndRatings.php');
    exit();
}
?>
