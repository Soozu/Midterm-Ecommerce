<?php
session_start();
include 'db.php';  // Your database connection file
require 'phpmailer\vendor\autoload.php';  // Composer autoload for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Generate a unique token for the password reset
        $token = bin2hex(random_bytes(50));

        // Insert the reset token and its expiry into the database
        $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))");
        $stmt->bind_param("is", $user['id'], $token);
        $stmt->execute();

        // Setup PHPMailer and send the reset link
        $resetLink = "http://localhost:8080/Midterm/reset-password.php?token=$token";
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tt9771675@gmail.com'; // Your Gmail address
            $mail->Password = 'vitopyrpujgsplcp'; // Your Gmail password or App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('tt9771675@gmail.com', 'Mabine\'s Cosmetic'); // Your name and email
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your Password Reset Link';
            $mail->Body = "
            <html>
            <head>
                <style>
                    .email-container {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        padding: 20px;
                        text-align: center;
                    }
                    .email-content {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }
                    .email-header {
                        background-color: #000;
                        color: #fff;
                        padding: 10px 0;
                        border-radius: 8px 8px 0 0;
                    }
                    .email-body {
                        text-align: left;
                        padding: 20px;
                    }
                    .email-body h2 {
                        color: #000;
                    }
                    .email-body p {
                        color: #333;
                    }
                    .email-footer {
                        background-color: #000;
                        color: #fff;
                        padding: 10px 0;
                        border-radius: 0 0 8px 8px;
                    }
                    .button {
                        display: inline-block;
                        padding: 10px 20px;
                        margin: 20px 0;
                        font-size: 16px;
                        color: #fff;
                        background-color: #007bff;
                        text-decoration: none;
                        border-radius: 5px;
                    }
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <div class='email-content'>
                        <div class='email-header'>
                            <h1>Mabine's Cosmetic</h1>
                        </div>
                        <div class='email-body'>
                            <h2>Password Reset Request</h2>
                            <p>Hi there,</p>
                            <p>We received a request to reset your password. Click the button below to reset it.</p>
                            <a href='$resetLink' class='button'>Reset Password</a>
                            <p>If you did not request a password reset, please ignore this email or contact support if you have questions.</p>
                            <p>Thanks,</p>
                            <p>Mabine's Cosmetic Team</p>
                        </div>
                        <div class='email-footer'>
                            <p>&copy; " . date("Y") . " Mabine's Cosmetic. All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>";

            $mail->send();
            // Redirect to a check-email page instead of echoing a message here
            header('Location: check-email.php');
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No account found with that email address.";
    }
}
?>
