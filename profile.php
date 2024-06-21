<?php
session_start();
include 'db.php';
include 'header.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch user details
$user_id = $_SESSION['id'];
$query = "SELECT username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Update user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $query = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssi', $username, $email, $password_hash, $user_id);
    } else {
        $query = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssi', $username, $email, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        echo "<p class='success'>Profile updated successfully.</p>";
    } else {
        echo "<p class='error'>Error updating profile. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <link rel="stylesheet" href="css/profile.css">
    <style>

    </style>
</head>
<body>
    <div class="profile-container">
        <h1>Profile Settings</h1>
        <form method="POST" action="profile.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

            <label for="password">Password (leave blank to keep current password)</label>
            <input type="password" id="password" name="password">

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
