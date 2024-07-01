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

        $emailBody = '
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f8f8f8;
                    color: #333;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    width: 100%;
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #ffffff;
                    padding: 20px;
                    border: 1px solid #ddd;
                }
                .header {
                    background-color: #000;
                    color: #fff;
                    padding: 10px;
                    text-align: center;
                }
                .content {
                    padding: 20px;
                    text-align: left;
                }
                .footer {
                    background-color: #000;
                    color: #fff;
                    text-align: center;
                    padding: 10px;
                    font-size: 12px;
                }
                a {
                    color: #000;
                    text-decoration: none;
                }
                .btn {
                    display: inline-block;
                    padding: 10px 20px;
                    color: #fff;
                    background-color: #000;
                    text-align: center;
                    border-radius: 5px;
                    text-decoration: none;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Mabine\'s Cosmetic</h1>
                </div>
                <div class="content">';

        if ($status == 'approved') {
            $token = bin2hex(random_bytes(50));
            $stmt = $conn->prepare("UPDATE refunds SET token = ? WHERE id = ?");
            $stmt->bind_param("si", $token, $refund_id);
            $stmt->execute();

            $refundLink = "http://localhost:8080/Midterm/Midterm-Ecommerce/processRefund.php?token=$token";
            $emailBody .= "
                    <p>Your refund request has been <strong>approved</strong>.</p>
                    <p>Click the button below to process your refund:</p>
                    <p><a class='btn' href=\"$refundLink\">Process Refund</a></p>";
        } else {
            $emailBody .= '
                    <p>Your refund request has been <strong>rejected</strong>.</p>
                    <p>If you have any questions, please contact us.</p>';
        }

        $emailBody .= '
                </div>
                <div class="footer">
                    <p>&copy; 2024 Mabine\'s Cosmetic. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';

        $mail->Body = $emailBody;
        $mail->send();
        header('Location: RefundAndRatings.php');
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
