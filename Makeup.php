<?php
session_start();
include 'db.php';
include 'header.php';

// Check if the user is logged in
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
    exit;
}

// Fetch products from the database
$query = "SELECT p.*, IFNULL(AVG(r.rating), 0) as avg_rating, COUNT(r.id) as num_sold FROM products p LEFT JOIN ratings r ON p.id = r.product_id WHERE category_id = 1 GROUP BY p.id"; // Assuming 1 is the category_id for Makeup
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Makeup Products</title>
    <link rel="stylesheet" href="css/makeup.css">
</head>
<body>
    <h1>Makeup Products</h1>
    <section class="products">
        <div id="products-container" class="product-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($product = $result->fetch_assoc()): ?>
                    <div class="product">
                        <figure>
                            <img src="img/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" onclick="window.location.href='product.php?id=<?= $product['id']; ?>'">
                        </figure>
                        <h3><?= htmlspecialchars($product['name']); ?></h3>
                        <p class="price">₱<?= number_format($product['price'], 2); ?></p>
                        <div class="rating">
                            <?php
                            $rating = round($product['avg_rating']);
                            for ($i = 0; $i < 5; $i++) {
                                if ($i < $rating) {
                                    echo '<span class="star filled">★</span>';
                                } else {
                                    echo '<span class="star">☆</span>';
                                }
                            }
                            ?>
                            <span class="sold">Sold: <?= $product['num_sold']; ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>
