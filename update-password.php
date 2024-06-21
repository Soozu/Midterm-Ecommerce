<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'], $_POST['token'])) {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Retrieve user_id from password_resets table
    $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Update user's password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_password, $user['user_id']);
        $stmt->execute();

        // Remove password reset token from the database (security measure)
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        // Redirect to login with notification
        $_SESSION['password_changed'] = "Your password has been updated successfully.";
        header('Location: login.php');
        exit();
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "Invalid request.";
}
?>
