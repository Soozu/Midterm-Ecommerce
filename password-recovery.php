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
            $mail->Username = 'tt9771675@gmail.com';
            $mail->Password = 'vitopyrpujgsplcp';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('tt9771675@gmail.com', 'Mabines Cosmetic');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your Password Reset Link';
            $mail->Body    = "Click here to reset your password: <a href=\"$resetLink\">$resetLink</a>";

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
