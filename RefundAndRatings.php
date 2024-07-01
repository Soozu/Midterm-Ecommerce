<?php
session_start();
include 'db.php';
require 'phpmailer/vendor/autoload.php';  // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch refunds and ratings data
$refunds_query = "
    SELECT refunds.*, users.username, users.email, products.name AS product_name
    FROM refunds
    JOIN users ON refunds.user_id = users.id
    JOIN products ON refunds.product_id = products.id
";
$refunds_result = $conn->query($refunds_query);

$ratings_query = "
    SELECT ratings.*, users.username, products.name AS product_name
    FROM ratings
    JOIN users ON ratings.user_id = users.id
    JOIN products ON ratings.product_id = products.id
";
$ratings_result = $conn->query($ratings_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $refund_id = $_POST['refund_id'];
    $status = $_POST['status'];
    $user_email = $_POST['user_email'];

    $stmt = $conn->prepare("UPDATE refunds SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $refund_id);
    $stmt->execute();

    // Send email notification
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tt9771675@gmail.com';  // Your email
        $mail->Password = 'vitopyrpujgsplcp';  // Your email password
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
            $emailBody .= '
                    <p>Your refund request has been <strong>approved</strong>.</p>
                    <p>We will process your refund shortly.</p>';
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
        $notification = "Email sent to $user_email";
    } catch (Exception $e) {
        $notification = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
// Process ready for refund
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_refund'])) {
    $refund_id = $_POST['refund_id'];
    $user_email = $_POST['user_email'];

    // Generate a unique token for the refund process link
    $token = bin2hex(random_bytes(50));
    $stmt = $conn->prepare("UPDATE refunds SET token = ? WHERE id = ?");
    $stmt->bind_param('si', $token, $refund_id);
    $stmt->execute();

    // Send email notification
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tt9771675@gmail.com';  // Your email
        $mail->Password = 'vitopyrpujgsplcp';  // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('tt9771675@gmail.com', 'Mabine\'s Cosmetic');
        $mail->addAddress($user_email);

        $mail->isHTML(true);
        $mail->Subject = 'Refund Process Link';
        $refundLink = "http://localhost:8080/Midterm/Midterm-Ecommerce/itemprocessRefund.php?token=$token";
        
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
                <div class="content">
                    <p>Your refund request has been <strong>approved</strong>.</p>
                    <p>Click the button below to process your refund:</p>
                    <p><a class="btn" href="' . $refundLink . '">Process Refund</a></p>
                </div>
                <div class="footer">
                    <p>&copy; 2024 Mabine\'s Cosmetic. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';

        $mail->Body = $emailBody;

        $mail->send();
        $notification = "Refund process link sent to $user_email";
    } catch (Exception $e) {
        $notification = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refunds and Ratings</title>
    <link rel="stylesheet" href="css/RefundAndRatings.css">
</head>
<body>
<div class="admin-container">
    <div class="admin-sidebar">
        <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="OrderManagement.php">Order Management</a></li>
            <li><a href="ProductManagement.php">Product Management</a></li>
            <li><a href="Categories.php">Categories</a></li>
            <li><a href="RefundAndRatings.php">Refund & Ratings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="admin-main">
        <div class="admin-header">
            <h1>Refunds and Ratings</h1>
            <p>Manage refunds and ratings here.</p>
            <?php if (isset($notification)): ?>
                <p class="notification"><?= htmlspecialchars($notification) ?></p>
            <?php endif; ?>
        </div>

       <!-- Refunds Section -->
<div class="admin-section">
    <h2>Refunds</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Email</th>
                <th>Product</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $refunds_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['reason']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td>
                    <form action="RefundAndRatings.php" method="POST" style="display:inline;">
                        <input type="hidden" name="refund_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="user_email" value="<?= $row['email'] ?>">
                        <select name="status">
                            <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="approved" <?= $row['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="rejected" <?= $row['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                        <button type="submit" name="update_status" class="admin-button">Update</button>
                    </form>
                    <?php if ($row['status'] == 'approved'): ?>
                        <form action="RefundAndRatings.php" method="POST" style="display:inline;">
                            <input type="hidden" name="refund_id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="user_email" value="<?= $row['email'] ?>">
                            <button type="submit" name="process_refund" class="admin-button">Process Refund</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Ratings Section -->
<div class="admin-section">
    <h2>Ratings</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Product</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $ratings_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['rating']) ?></td>
                <td><?= htmlspecialchars($row['comment']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</div>
</div>
</body>
</html>
