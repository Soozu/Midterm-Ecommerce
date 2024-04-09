<?php
session_start();
include 'db.php'; // Ensure this path is correct

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'You must log in to add items to your cart.']);
    exit;
}

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = isset($_POST['productId']) ? intval($_POST['productId']) : 0;
    $quantity = 1; // Default quantity to 1, can be changed as per your form input

    // Check if the product exists in the database
    if ($productId > 0) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();

            // Check if cart is already created, if not create one
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Check if the product is already in the cart, if so increment the quantity
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] += $quantity;
            } else {
                // Add to cart with quantity
                $_SESSION['cart'][$productId] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $quantity
                ];
            }

            echo json_encode(['status' => 'success', 'message' => 'Product added to cart.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Product does not exist.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid product ID.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/cart.css>
</head>
<body>
<div class="loader" id="loader"></div>
<?php include 'header.php'; ?>

    <main>
        <section class="cart-container">
        <?php
$totalItems = 0;
$totalPrice = 0.00;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $totalItems += $item['quantity'];
        $totalPrice += $item['quantity'] * $item['price'];
    }
}
?>
<span class="total-items">Total (<?php echo $totalItems; ?> items): </span>
<span class="total-price">₱<?php echo number_format($totalPrice, 2); ?></span>

        <div class="cart-items">
    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <div class="cart-item">
                <span class="item-name"><?php echo htmlspecialchars($item['name']); ?></span>
                <span class="item-quantity"><?php echo htmlspecialchars($item['quantity']); ?></span>
                <span class="item-price">₱<?php echo number_format($item['price'], 2); ?></span>
                <!-- Add more item details as needed -->
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
<script>
  var loginError = <?php echo json_encode(isset($_SESSION['login_error']) && $_SESSION['login_error']); ?>;
  var isLoggedIn = <?php echo json_encode(isset($_SESSION['loggedin']) && $_SESSION['loggedin']); ?>;
</script>
    <script src="scripts/cart.js"></script>
    <script src="scripts/modal.js"></script>
</body>
</html>