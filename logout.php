<?php
session_start();
unset($_SESSION['user_id']); // Unset the user_id or
session_destroy(); // Destroy the entire session
header("Location: index.php"); // Redirect to the homepage or login page
exit();

