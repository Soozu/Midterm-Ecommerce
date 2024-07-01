<?php
session_start();
include 'db.php';
require 'phpmailer/vendor/autoload.php';  // Composer autoload for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $refund_id = $_POST['refund_id'];
    $user_email = $_POST['user_email'];
    $status = $_POST['status'];

    // Update refund status
    $stmt = $conn->prepare("UPDATE refunds SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $refund_id);
    $stmt->execute();

    // Send email notification
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tt9771675@gmail.com';
        $mail->Password = 'vitopyrpujgsplcp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('tt9771675@gmail.com', 'Mabine\'s Cosmetic');
        $mail->addAddress($user_email);

        $mail->isHTML(true);
        $mail->Subject = 'Refund Request Status';

        if ($status == 'approved') {
            $token = bin2hex(random_bytes(50));
            $stmt = $conn->prepare("UPDATE refunds SET token = ? WHERE id = ?");
            $stmt->bind_param("si", $token, $refund_id);
            $stmt->execute();

            $refundLink = "http://localhost:80/Midterm/itemprocessRefund.php?token=$token";
            $mail->Body    = "Your refund request has been approved. Click <a href=\"$refundLink\">here</a> to process your refund.";
        } else {
            $mail->Body    = 'Your refund request has been rejected. If you have any questions, please contact us.';
        }

        $mail->send();
        header('Location: RefundAndRatings.php');
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
