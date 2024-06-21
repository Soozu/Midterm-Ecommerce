<?php
session_start();
include 'db.php';
include 'header.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<p>Please log in to view your cart.</p>";
    include 'footer.php';
    exit;
}

$user_id = $_SESSION['id'];
$stmt = $conn->prepare("SELECT p.name, p.price, p.image, p.id as product_id, ci.quantity FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.user_id = ? AND ci.status = 'active'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo '<div class="cart-container">';
echo '<h1 class="cart-header">Your Shopping Cart</h1>';
if ($result->num_rows > 0) {
    $total = 0;
    while ($row = $result->fetch_assoc()) {
        $item_total = $row['quantity'] * $row['price'];
        $total += $item_total;
        echo '<div class="cart-item">';
        echo '<img src="img/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '" class="cart-item-image">';
        echo '<div class="item-details">' . htmlspecialchars($row['name']) . ' - $' . number_format($row['price'], 2) . '</div>';
        echo '<div class="quantity-controls">';
        echo '<button onclick="updateQuantity(' . $row['product_id'] . ', ' . max($row['quantity'] - 1, 1) . ')">-</button>';  // Ensures quantity cannot go below 1
        echo ' ' . $row['quantity'] . ' ';
        echo '<button onclick="updateQuantity(' . $row['product_id'] . ', ' . ($row['quantity'] + 1) . ')">+</button>';
        echo '</div>';
        echo '<div class="item-price">₱' . number_format($item_total, 2) . '</div>';
        echo '<button class="remove-button" onclick="removeFromCart(' . $row['product_id'] . ')">Remove</button>';
        echo '</div>';
    }
    echo '<div class="total-price">Total: ₱' . number_format($total, 2) . '</div>';
    echo '<button onclick="window.location.href=\'checkout.php\'">Check Out</button>';
} else {
    echo "<p>You have no items in your cart.</p>";
}
echo '</div>';


?>

<script>
function updateQuantity(productId, newQuantity) {
    fetch('updateCartQuantity.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId, quantity: newQuantity })
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload(); // Reload the page to update the cart display
        } else {
            alert(data.message);
        }
    });
}

function removeFromCart(productId) {
    console.log("Removing product with ID:", productId);  // Debug log
    fetch('removeFromCart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Removed from cart successfully!');
            window.location.reload(); // Reload to update the cart display
        } else {
            alert('Failed to remove from cart: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error removing from cart: ' + error);
        console.error('Error removing from cart:', error);
    });
}
</script>

<style>
body {
    background-color: #ffffff; /* White background */
    font-family: Arial, sans-serif;
    color: #000000; /* Black text */
}
.cart-container {
    max-width: 800px;
    margin: 170px auto;
    background: #ffffff; /* White background */
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.cart-header {
    text-align: center;
    margin-bottom: 20px;
}
.cart-item {
    border-bottom: 1px solid #ddd;
    padding: 10px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.cart-item:last-child {
    border-bottom: none;
}
.cart-item img {
    width: 50px;
    height: 50px;
    margin-right: 10px;
    border-radius: 5px;
}
.item-details {
    flex-grow: 1;
}
.item-price, .quantity-controls {
    margin-left: 20px;
    font-weight: bold;
}
.total-price {
    text-align: right;
    margin-top: 20px;
    font-size: 1.2em;
    font-weight: bold;
}
button {
    padding: 8px 16px;
    background-color: #000000; /* Black button */
    color: #ffffff; /* White text */
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
button:hover {
    background-color: #444444; /* Darker black button */
}
.remove-button {
    background-color: #ff0000; /* Red button */
    color: #ffffff; /* White text */
}
.remove-button:hover {
    background-color: #cc0000; /* Darker red button */
}
</style>
