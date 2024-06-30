<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/viewCart.css">

</head>
<body>
    <?php
    session_start();
    include 'db.php';
    include 'header.php';

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        echo '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Login Required</title>
            <link rel="stylesheet" href="css/login-message.css">
        </head>
        <body>
            <div class="login-message">
                <p>Please log in to view your cart.</p>
                <a href="login.php">Login</a>
            </div>
        </body>
        </html>';
        include 'footer.php';
        exit;
    }

    $user_id = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT p.name, p.price, p.id as product_id, ci.quantity FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.user_id = ? AND ci.status = 'active'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<form id="cartForm" action="checkout.php" method="POST">';
    echo '<div class="cart-container">';
    echo '<h1 class="cart-header">Your Shopping Cart</h1>';
    if ($result->num_rows > 0) {
        $total = 0;
        while ($row = $result->fetch_assoc()) {
            $item_total = $row['quantity'] * $row['price'];
            $total += $item_total;
            echo '<div class="cart-item">';
            echo '<input type="checkbox" name="selected_items[]" value="' . $row['product_id'] . '">';
            echo '<div class="item-details">' . htmlspecialchars($row['name']) . ' - $' . number_format($row['price'], 2) . '</div>';
            echo '<div class="quantity-controls">';
            echo '<button type="button" onclick="updateQuantity(' . $row['product_id'] . ', ' . max($row['quantity'] - 1, 1) . ')">-</button>';
            echo ' ' . $row['quantity'] . ' ';
            echo '<button type="button" onclick="updateQuantity(' . $row['product_id'] . ', ' . ($row['quantity'] + 1) . ')">+</button>';
            echo '</div>';
            echo '<div class="item-price">₱' . number_format($item_total, 2) . '</div>';
            echo '<button type="button" onclick="removeFromCart(' . $row['product_id'] . ')">Remove</button>';
            echo '</div>';
        }
        echo '<div class="total-price">Total: ₱' . number_format($total, 2) . '</div>';
        echo '<button type="submit" onclick="return validateSelection()">Check Out</button>';
    } else {
        echo "<p>You have no items in your cart.</p>";
    }
    echo '</div>';
    echo '</form>';
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

        function validateSelection() {
            var checkboxes = document.querySelectorAll('input[name="selected_items[]"]:checked');
            if (checkboxes.length === 0) {
                alert("Please select at least one item to proceed with the checkout.");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
</body>
</html>
