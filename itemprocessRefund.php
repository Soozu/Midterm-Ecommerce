<?php
session_start();
include 'db.php';
require 'phpmailer/vendor/autoload.php';  // Composer autoload for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $user_email = $_POST['user_email'];
    $reason = $_POST['reason'];
    $contact_number = $_POST['contact_number'];
    $product_image = '';

    // Handle file upload securely
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $temp_name = $_FILES['product_image']['tmp_name'];
        $image_name = basename($_FILES['product_image']['name']);
        $upload_dir = 'img/';

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($temp_name);

        if (in_array($file_type, $allowed_types) && $_FILES['product_image']['size'] <= 2000000) {
            $image_path = $upload_dir . $image_name;
            if (move_uploaded_file($temp_name, $image_path)) {
                $product_image = $image_name;
            }
        }
    }

    // Validate the token
    $stmt = $conn->prepare("SELECT * FROM refunds WHERE token = ? AND status = 'approved'");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $refund = $result->fetch_assoc();
        
        // Update refund status to 'processed'
        $stmt = $conn->prepare("UPDATE refunds SET status = 'processed', product_image = ?, reason = ?, contact_number = ? WHERE id = ?");
        $stmt->bind_param("sssi", $product_image, $reason, $contact_number, $refund['id']);
        $stmt->execute();

        // Send notification email
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
            $mail->Subject = 'Refund Processed';
            $mail->Body    = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Refund Processed</title>
                    <style type="text/css">
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                            background-color: #f4f4f4;
                        }
                        .container {
                            width: 100%;
                            padding: 20px;
                            background-color: #ffffff;
                            border: 1px solid #dddddd;
                            max-width: 600px;
                            margin: 0 auto;
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        }
                        .header {
                            background-color: #4CAF50;
                            color: white;
                            padding: 10px;
                            text-align: center;
                            border-radius: 5px 5px 0 0;
                        }
                        .content {
                            padding: 20px;
                        }
                        .content h2 {
                            color: #333333;
                        }
                        .content p {
                            color: #555555;
                            line-height: 1.5;
                        }
                        .content a {
                            display: inline-block;
                            padding: 10px 20px;
                            margin-top: 20px;
                            background-color: #4CAF50;
                            color: white;
                            text-decoration: none;
                            border-radius: 5px;
                        }
                        .footer {
                            text-align: center;
                            padding: 10px;
                            background-color: #f1f1f1;
                            border-radius: 0 0 5px 5px;
                            color: #555555;
                            font-size: 12px;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h1>Mabine\'s Cosmetic</h1>
                        </div>
                        <div class="content">
                            <h2>Refund Processed</h2>
                            <p>Dear Customer,</p>
                            <p>Your refund request has been processed successfully. Please wait for the delivery team to claim the item.</p>
                            <p>If you have any questions, feel free to contact our support team.</p>
                            <a href="http://localhost/Midterm/Midterm-Ecommerce/index.php">Visit Our Store</a>
                        </div>
                        <div class="footer">
                            <p>&copy; 2024 Mabine\'s Cosmetic. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>';

            $mail->send();
            $notification = "Refund process completed and email sent to $user_email";
        } catch (Exception $e) {
            $notification = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        header('Location: Order.php');
    } else {
        $notification = "Invalid or expired token.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Process Refund</title>
    <link rel="stylesheet" href="css/itemprocessRefund.css">
</head>
<body>
<div class="form-container">
    <h2>Process Refund</h2>
    <?php if (isset($notification)): ?>
        <p class="notification"><?= htmlspecialchars($notification) ?></p>
    <?php endif; ?>
    <form action="itemprocessRefund.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
        <label for="product_image">Picture of Item:</label>
        <input type="file" id="product_image" name="product_image" accept="image/*" required>
        
        <label for="reason">Reason for refund:</label>
        <textarea id="reason" name="reason" rows="4" required></textarea>
        
        <label for="user_email">Email:</label>
        <input type="email" id="user_email" name="user_email" required>
        
        <label for="contact_number">Contact Number:</label>
        <input type="text" id="contact_number" name="contact_number" required>
        
        <button type="submit">Refund Item</button>
        <p>Notification: Wait for the delivery team to claim the item.</p>
    </form>
</div>
</body>
</html>
