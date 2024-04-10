<?php
session_start();
include 'db.php'; // Include your database connection

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape the username to protect against SQL injections
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; // No need to escape the password, it will not be used in a query

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user with that username exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password against the hash in the database
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role']; // Store the user's role

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: dashboard.php'); // Redirect to the admin dashboard
            } elseif ($user['role'] === 'seller') {
                header('Location: seller_dashboard.php'); // Redirect to the seller dashboard
            } else {
                header('Location: index.php'); // Default redirect for customers and other roles
            }
            exit;
        } else {
            // If password doesn't match, set an error message
            $_SESSION['login_error'] = 'Incorrect username or password.';
            header('Location: index.php'); // Redirect back to the index page
            exit;
        }
    } else {
        // If no user exists with the given username, set an error message
        $_SESSION['login_error'] = 'Incorrect username or password.';
        header('Location: index.php'); // Redirect back to the index page
        exit;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If the form hasn't been submitted, redirect to the login form
    header('Location: index.php');
    exit;
}
?>
