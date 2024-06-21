<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check Your Email</title>
    <style>
/* Specific styles for check-email page */
.check-email-container {
    background-color: #fff; /* White background */
    color: #000; /* Black text */
    font-family: Arial, sans-serif;
    text-align: center;
    margin: 0;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.check-email-container h1 {
    color: #333;
}

.check-email-container a {
    color: #555;
    text-decoration: none;
    background-color: #f0f0f0;
    padding: 10px 15px;
    border-radius: 5px;
    margin-top: 20px;
    display: inline-block;
}

.check-email-container a:hover {
    background-color: #e0e0e0;
}


    </style>
</head>
<body>
    <div class="check-email-container">
        <h1>Please Check Your Email</h1>
        <p>A link to reset your password has been sent to your email address. If you do not receive an email, please ensure you entered the correct email address or <a href="forgot-password.php">try again</a>.</p>
        <p><a href="login.php">Return to Login</a></p>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>
