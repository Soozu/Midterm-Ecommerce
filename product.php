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
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 0;
}

h1 {
    text-align: center;
    margin: 20px 0;
    color: #000;
}

.product-container {
    display: flex;
    width: 100%;
    margin: 100px auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    flex-wrap: wrap;
    margin-top: 175px;
    margin-bottom: 10px;
}

.product-image {
    flex: 1;
    margin-right: 20px;
    width: 250px;
    height: 250px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.product-image img {
    max-width: 100%;
    border-radius: 8px;
    width: 250px;
    height: 250px;
}

.product-details {
    flex: 2;
    max-width: 100%;
}

.product-title {
    font-size: 32px;
    margin-bottom: 10px;
    text-align: left;
}

.product-rating {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.product-rating .stars {
    display: flex;
    margin-right: 10px;
}

.product-rating .stars .star {
    font-size: 1.5em;
    color: #f39c12;
}

.product-rating .stars .star.empty {
    color: #ccc;
}

.product-rating .rating-count {
    margin-right: 10px;
    font-size: 1.2em;
}

.product-rating .sold-count {
    color: #999;
    font-size: 1.2em;
}

.product-price {
    font-size: 36px;
    color: #e74c3c;
    margin-bottom: 20px;
}

.product-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.product-actions a {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    text-decoration: none;
    display: inline-block;
}

.product-actions .button {
    background-color: #3498db;
    color: white;
    transition: background-color 0.3s ease;
}

.product-actions .button:hover {
    background-color: #2980b9;
}


.comments-section, .add-comment {
    width: 50%;
    margin: 20px auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.comments-section .comment {
    margin-bottom: 20px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
}

.comments-section .comment .stars {
    display: flex;
}

.comments-section .comment .star {
    font-size: 1em;
    color: #ccc;
}

.comments-section .comment .star.filled {
    color: #f39c12;
}

.add-comment form {
    display: flex;
    flex-direction: column;
}

.add-comment label {
    margin: 10px 0 5px;
    color: #333;
}

.add-comment textarea {
    padding: 10px;
    margin: 5px 0 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
    color: #000;
}

.add-comment input[type="submit"] {
    background-color: #000;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 20px;
    transition: background-color 0.3s ease;
}

.add-comment input[type="submit"]:hover {
    background-color: #555;
}

    </style>
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
                <a href="addToCart.php?product_id=<?= $product['id']; ?>&checkout=true" class="button" style="background-color: #e74c3c;">Buy Now</a>
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
