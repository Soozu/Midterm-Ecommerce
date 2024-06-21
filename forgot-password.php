<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'header.php'; ?>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <style>
        html, body {
    height: 100%;  /* Ensures that the HTML and body tags take full viewport height */
    margin: 0;     /* Removes default margin */
    display: flex;
    flex-direction: column; /* Makes children (content and footer) layout in a column */
}
        body {
            font-family: Arial, sans-serif; /* Modern and readable typeface */
            background-color: #fff; /* White background */
            color: #333; /* Dark text for readability */
            padding: 20px; /* Padding around the content */
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        h2 {
            color: #000; /* Black color for headings */
        }
        form {
            background-color: #f7f7f7; /* Light grey background for the form */
            border: 2px solid #ddd; /* Subtle border */
            padding: 20px;
            width: 300px; /* Fixed width */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow for 3D effect */
        }
        label {
            margin-bottom: 10px; /* Space below labels */
            display: block; /* Ensure labels appear on their own line */
        }
        input[type="email"], input[type="submit"] {
            width: 100%; /* Full width inputs */
            padding: 8px; /* Comfortable padding inside inputs */
            margin-top: 5px; /* Space above inputs */
            margin-bottom: 20px; /* Space below inputs */
            box-sizing: border-box; /* Include padding in width calculation */
        }
        input[type="submit"] {
            background-color: #000; /* Black background for submit button */
            color: #fff; /* White text for submit button */
            cursor: pointer; /* Pointer cursor on hover */
            border: none; /* No border */
        }
        input[type="submit"]:hover {
            background-color: #333; /* Darker grey on hover */
        }
    </style>
</head>
<body>
    <h2>Reset Your Password</h2>
    <form action="password-recovery.php" method="post">
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Send Reset Link">
    </form>

</body>
</html>
