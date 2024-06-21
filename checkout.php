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

// Fetch cart items for the logged-in user
$cart_query = "SELECT cart_items.*, products.name, products.price 
               FROM cart_items 
               INNER JOIN products ON cart_items.product_id = products.id 
               WHERE cart_items.user_id = ? AND cart_items.status = 'active'";
$cart_stmt = $conn->prepare($cart_query);
$cart_stmt->bind_param('i', $user_id);
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
    <style>
        /* Basic Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            display: block;
            background-color: #f0f0f0; /* Light gray background for better contrast */
            color: #333; /* Dark gray font color for better readability */
            font-family: Arial, sans-serif; /* Clean and simple font */
        }
        .checkout-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px;
            max-width: 1200px;
            margin: 150px auto 0; /* Adjusted margin to create more space from the header */
        }
        .checkout-section {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 3;
            margin-right: 20px;
        }
        .order-summary {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1;
        }
        .checkout-section h2, .checkout-section h3, .order-summary h4 {
            color: #000; /* Black color for headers */
            margin-bottom: 10px;
        }
        .checkout-section p, .order-summary p {
            margin: 10px 0;
            color: #333; /* Dark gray font color */
        }
        .checkout-section form {
            display: flex;
            flex-direction: column;
        }
        .checkout-section label {
            margin: 10px 0 5px;
            color: #333; /* Dark gray font color */
        }
        .checkout-section input[type="text"], 
        .checkout-section input[type="email"],
        .checkout-section input[type="number"] {
            padding: 10px;
            margin: 5px 0 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            color: #000;
        }
        .checkout-section input[type="submit"] {
            background-color: #000;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        .checkout-section input[type="submit"]:hover {
            background-color: #555;
        }
        .uk-text-muted {
            color: #777; /* Medium gray for muted text */
        }
        .uk-text-danger {
            color: #ff0000; /* Red for danger text */
        }
        .uk-button-primary {
            background-color: #000;
            color: #fff;
        }
        .uk-button-primary:hover {
            background-color: #555;
        }
        .order-summary div {
            margin-bottom: 10px;
        }
        .order-summary .uk-text-right {
            text-align: right;
        }
        .order-summary .uk-text-muted,
        .order-summary .uk-text-meta,
        .order-summary .uk-text-danger,
        .order-summary .uk-text-lead,
        .order-summary .uk-text-bolder {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
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
