<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/reset-password-styles.css"> <!-- Link to specific CSS for reset-password -->
</head>
<body>
    <div class="reset-password-container">
        <?php
        include 'db.php';
        $token = $_GET['token'] ?? '';

        // Verify the token
        $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            echo '<form action="update-password.php" method="post">
                    <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                    <label for="password">New Password:</label>
                    <input type="password" id="password" name="password" required>
                    <input type="submit" value="Reset Password">
                  </form>';
        } else {
            echo "Invalid or expired token.";
        }
        ?>
    </div>
</body>
</html>
