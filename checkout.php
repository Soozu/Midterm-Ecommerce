<?php
session_start();
include 'db.php';
include 'header.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch user details
$user_id = $_SESSION['id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Check if direct checkout from product page
if (isset($_SESSION['checkout_product_id'])) {
    $product_id = $_SESSION['checkout_product_id'];
    unset($_SESSION['checkout_product_id']);
    
    $cart_query = "SELECT cart_items.*, products.name, products.price 
                   FROM cart_items 
                   INNER JOIN products ON cart_items.product_id = products.id 
                   WHERE cart_items.user_id = ? AND cart_items.product_id = ? AND cart_items.status = 'active'";
    $cart_stmt = $conn->prepare($cart_query);
    $cart_stmt->bind_param('ii', $user_id, $product_id);
} else {
    // Get selected items from the cart
    $selected_items = isset($_POST['selected_items']) ? $_POST['selected_items'] : [];

    if (empty($selected_items)) {
        echo "<p>No items selected for checkout.</p>";
        include 'footer.php';
        exit;
    }

    $placeholders = implode(',', array_fill(0, count($selected_items), '?'));
    $types = str_repeat('i', count($selected_items));

    $cart_query = "SELECT cart_items.*, products.name, products.price 
                   FROM cart_items 
                   INNER JOIN products ON cart_items.product_id = products.id 
                   WHERE cart_items.user_id = ? AND cart_items.product_id IN ($placeholders) AND cart_items.status = 'active'";
    $cart_stmt = $conn->prepare($cart_query);
    $cart_stmt->bind_param('i' . $types, $user_id, ...$selected_items);
}

$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

// Calculate total price
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
    <?php if (isset($_SESSION['error'])): ?>
        <div class="notification error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="notification success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <div class="checkout-container">
        <!-- Checkout Section -->
        <div class="checkout-section">
            <h2>Checkout</h2>

            <!-- Contact Information -->
            <div class="checkout-section">
                <h3>Contact Information</h3>
                <p>Name: <?= htmlspecialchars($user['username']); ?></p>
                <p>Email: <?= htmlspecialchars($user['email']); ?></p>
            </div>

            <!-- Shipping Information -->
            <div class="checkout-section">
                <h3>Shipping Information</h3>
                <form method="POST" action="processCheckout.php">
                    <label for="shipping_address">Shipping Address:</label>
                    <input type="text" id="shipping_address" name="shipping_address" required>
                    <label for="shipping_city">City:</label>
                    <input type="text" id="shipping_city" name="shipping_city" required>
                    <label for="shipping_postal_code">Postal Code:</label>
                    <input type="text" id="shipping_postal_code" name="shipping_postal_code" required>
                    <label for="shipping_country">Country:</label>
                    <input type="text" id="shipping_country" name="shipping_country" required>

                    <!-- Payment Information -->
                    <h3>Payment Information</h3>
                    <p>Payment Method: Cash On Delivery</p>

                    <input type="hidden" name="total_price" value="<?= $total_price; ?>">
                    <?php foreach ($cart_items as $item): ?>
                        <input type="hidden" name="cart_items[<?= $item['product_id']; ?>]" value="<?= $item['quantity']; ?>">
                    <?php endforeach; ?>

                    <input type="submit" value="Place Order">
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="order-summary uk-card uk-card-default uk-card-small tm-ignore-container">
            <section class="uk-card-body">
                <h4>Items in order</h4>
                <?php foreach ($cart_items as $item): ?>
                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-expand">
                        <div class="uk-text-small"><?= htmlspecialchars($item['name']); ?></div>
                        <div class="uk-text-meta"><?= $item['quantity']; ?> × ₱<?= number_format($item['price'], 2); ?></div>
                    </div>
                    <div class="uk-text-right">
                        <div>₱<?= number_format($item['price'] * $item['quantity'], 2); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </section>
            <section class="uk-card-body">
                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-expand">
                        <div class="uk-text-muted">Shipping</div>
                    </div>
                    <div class="uk-text-right">
                        <div>Pick up from store</div>
                        <div class="uk-text-meta">Free, tomorrow</div>
                    </div>
                </div>
                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-expand">
                        <div class="uk-text-muted">Payment</div>
                    </div>
                    <div class="uk-text-right">
                        <div>Cash On Delivery</div>
                    </div>
                </div>
            </section>
            <section class="uk-card-body">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <div class="uk-width-expand">
                        <div class="uk-text-muted">Total</div>
                    </div>
                    <div class="uk-text-right">
                        <div class="uk-text-lead uk-text-bolder">₱<?= number_format($total_price, 2); ?></div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
