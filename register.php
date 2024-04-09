<?php
session_start();
include 'db.php'; // Include your database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = "customer"; // By default, users are registered as customers

    // Check if passwords match
    if ($password === $confirm_password) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // First, check if the username or email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Username or email already exists.";
        } else {
            // Username and email are unique, proceed to insert the new user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

            // Execute the query
            if ($stmt->execute()) {
                echo "New record created successfully";
                $_SESSION['username'] = $username; // Set the session with the new user
                // Redirect to a new page or display success message
                header("Location: index.php"); // Or wherever you want to redirect
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    } else {
        echo "Passwords do not match.";
    }

    $conn->close();
}
?>
