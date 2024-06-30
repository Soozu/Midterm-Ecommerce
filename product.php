<?php
session_start();
include 'db.php';
include 'header.php';

// Fetch product details
$product_id = $_GET['id'];
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$product_result = $stmt->get_result();
$product = $product_result->fetch_assoc();

// Fetch product ratings and comments
$ratings_query = "SELECT ratings.*, users.username FROM ratings JOIN users ON ratings.user_id = users.id WHERE product_id = ?";
$ratings_stmt = $conn->prepare($ratings_query);
$ratings_stmt->bind_param('i', $product_id);
$ratings_stmt->execute();
$ratings_result = $ratings_stmt->get_result();

// Calculate average rating
$avg_rating_query = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_ratings FROM ratings WHERE product_id = ?";
$avg_rating_stmt = $conn->prepare($avg_rating_query);
$avg_rating_stmt->bind_param('i', $product_id);
$avg_rating_stmt->execute();
$avg_rating_result = $avg_rating_stmt->get_result();
$avg_rating = $avg_rating_result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="css/product.css">


</head>
<body>
    <div class="product-container">
        <div class="product-image">
            <img src="img/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
        </div>
        <div class="product-details">
            <h1 class="product-title"><?= htmlspecialchars($product['name']); ?></h1>
            <div class="product-rating">
                <div class="stars">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= $avg_rating['avg_rating'] ? '<span class="star">&#9733;</span>' : '<span class="star empty">&#9733;</span>';
                    }
                    ?>
                </div>
                <div class="rating-count">(<?= $avg_rating['total_ratings']; ?> Ratings)</div>
                <div class="sold-count"><?= $product['num_sold']; ?> Sold</div>
            </div>
            <div class="product-price">₱<?= number_format($product['price'], 2); ?></div>
            <div class="product-actions">
            <a href="addToCart.php?product_id=<?= $product['id']; ?>" class="button">Add to Cart</a>
            <a href="addToCart.php?product_id=<?= $product['id']; ?>&checkout=true" class="button">Buy Now</a>
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="comments-section">
        <h2>Customer Reviews</h2>
        <?php while ($rating = $ratings_result->fetch_assoc()): ?>
            <div class="comment">
                <div class="stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star <?= $i <= $rating['rating'] ? 'filled' : ''; ?>">&#9733;</span>
                    <?php endfor; ?>
                </div>
                <p><?= htmlspecialchars($rating['comment']); ?></p>
                <p><strong><?= htmlspecialchars($rating['username']); ?></strong></p>
                <p><small><?= htmlspecialchars($rating['created_at']); ?></small></p>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
