<?php
session_start();
include 'db.php';  // Ensure this path is correct

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $_SESSION['login_required'] = true;  // Set a session variable
    header("Location: login.php");  // Redirect to the login page
    exit;
}

// Fetch makeup products from the database
$query = "SELECT * FROM products WHERE category_id = 1";  // Assuming '1' is the ID for Makeup
$result = $conn->query($query);

include 'header.php';  // Adjust the path as needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Makeup Products</title>
    <link rel="stylesheet" href="css/Makeup.css"> <!-- Assuming you have a styles.css file -->
</head>
<body>
    <section class="makeup">
        <h1 align="center">Makeup Products</h1>
        <div class="makeup-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($product = $result->fetch_assoc()): ?>
                    <?php $isInStock = $product['stock_quantity'] > 0; ?>
                    <div class="makeup-item">
                        <figure>
                            <img src="img/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                            <figcaption><?= htmlspecialchars($product['description']); ?></figcaption>
                        </figure>
                        <h3><?= htmlspecialchars($product['name']); ?></h3>
                        <p class="price">$<?= number_format($product['price'], 2); ?></p>
                        <p class="stock"><?= $isInStock ? "Stock: " . $product['stock_quantity'] : "Out of Stock"; ?></p>
                        <?php if ($isInStock): ?>
                            <a href="addToCart.php?product_id=<?= $product['id']; ?>" class="button">Add to Cart</a>
                            <a href="addToCart.php?product_id=<?= $product['id']; ?>&checkout=true" class="button">Buy Now</a>
                        <?php else: ?>
                            <button disabled class="button out-of-stock">Out of Stock</button>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>

