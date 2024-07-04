<?php
session_start();
include 'db.php';
include 'header.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

$cart_query = "SELECT cart_items.*, products.name, products.price 
               FROM cart_items 
               INNER JOIN products ON cart_items.product_id = products.id 
               WHERE cart_items.user_id = ? AND cart_items.status = 'active'";
$cart_stmt = $conn->prepare($cart_query);
$cart_stmt->bind_param('i', $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

$total_price = 0;
$cart_items = [];
while ($item = $cart_result->fetch_assoc()) {
    $total_price += $item['price'] * $item['quantity'];
    $cart_items[] = $item;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
    <div class="checkout-container">
        <div class="checkout-section">
            <h2>Checkout</h2>
            <div class="checkout-section">
                <h3>Contact Information</h3>
                <p>Name: <?= htmlspecialchars($user['username']); ?></p>
                <p>Email: <?= htmlspecialchars($user['email']); ?></p>
            </div>
            <div class="checkout-section">
                <h3>Shipping Information</h3>
                <form id="checkoutForm" method="POST" action="processCheckout.php">
                    <label for="shipping_address">Shipping Address:</label>
                    <input type="text" id="shipping_address" name="shipping_address" required>
                    <label for="shipping_city">City:</label>
                    <input type="text" id="shipping_city" name="shipping_city" required>
                    <label for="shipping_postal_code">Postal Code:</label>
                    <input type="text" id="shipping_postal_code" name="shipping_postal_code" required>
                    <label for="shipping_country">Country:</label>
                    <input type="text" id="shipping_country" name="shipping_country" required>
                    <h3>Payment Information</h3>
                    <p>Payment Method: QR Code Payment</p>
                    <input type="hidden" name="total_price" value="<?= $total_price; ?>">
                    <?php foreach ($cart_items as $item): ?>
                        <input type="hidden" name="cart_items[<?= $item['product_id']; ?>]" value="<?= $item['quantity']; ?>">
                    <?php endforeach; ?>
                    <button type="button" onclick="validateForm()">Place Order</button>
                </form>
            </div>
        </div>
        <div class="order-summary">
            <section class="order-summary-body">
                <h4>Items in order</h4>
                <?php foreach ($cart_items as $item): ?>
                <div class="order-summary-item">
                    <div><?= htmlspecialchars($item['name']); ?></div>
                    <div><?= $item['quantity']; ?> × ₱<?= number_format($item['price'], 2); ?></div>
                    <div>₱<?= number_format($item['price'] * $item['quantity'], 2); ?></div>
                </div>
                <?php endforeach; ?>
            </section>
            <section class="order-summary-body">
                <div class="order-summary-item">
                    <div>Shipping</div>
                    <div>Pick up from store</div>
                    <div>Free, tomorrow</div>
                </div>
                <div class="order-summary-item">
                    <div>Payment</div>
                    <div>QR Code Payment</div>
                </div>
            </section>
            <section class="order-summary-body">
                <div class="order-summary-total">
                    <div>Total</div>
                    <div>₱<?= number_format($total_price, 2); ?></div>
                </div>
            </section>
        </div>
    </div>

    <!-- Modal for QR Code -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Scan QR Code to Pay</h2>
            <img id="qrCodeImage" src="" alt="QR Code" style="height: 400px;">
            <button onclick="markPaymentAsDone()">Done</button>
        </div>
    </div>

    <script>
    function validateForm() {
        const form = document.getElementById('checkoutForm');
        if (form.checkValidity()) {
            openModal(<?= $total_price ?>);
        } else {
            form.reportValidity();
        }
    }

    function openModal(total) {
        document.getElementById('qrCodeImage').src = 'qrcode/' + total + '.jpg';
        document.getElementById('paymentModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('paymentModal').style.display = 'none';
    }

    function markPaymentAsDone() {
        fetch('markPayment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: <?= $user_id ?>, total: <?= $total_price ?> })
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payment marked as done. Please wait for admin confirmation.');
                closeModal();
                document.getElementById('checkoutForm').submit();
            } else {
                alert('Error marking payment as done: ' + data.message);
            }
        });
    }
    </script>
</body>
</html>
