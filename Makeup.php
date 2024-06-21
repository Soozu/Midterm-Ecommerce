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
    <style>
        /* Products Section */
.products {
    text-align: center;
    padding: 20px;
}

.product-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.product {
    background: #fff;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    width: 300px;
    margin: 190px auto;
    padding: 10px;
}

.product img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.product h3 {
    margin: 15px 0;
}

.product p {
    margin: 10px 0;
}

.product .price {
    font-size: 1.2em;
    color: #333;
}

.product .stock {
    font-size: 1em;
    color: #999;
}

.product .button {
    display: block;
    width: calc(100% - 20px);
    margin: 10px auto;
    padding: 10px;
    text-align: center;
    border: none;
    background-color: #000;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
}

.product .button:hover {
    background-color: #444;
}

.product .out-of-stock {
    background-color: #f44336;
    color: #fff;
}

/* Description Toggle */
.description-short {
    display: block;
}
.description-full {
    display: none;
}
.read-more {
    color: #007BFF;
    cursor: pointer;
}
    </style>
</head>
<body>
 <!-- Makeup Products Section -->
 <section class="products">
        <h1>Makeup Products</h1>
        <div class="product-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($product = $result->fetch_assoc()): ?>
                    <?php $isInStock = $product['stock_quantity'] > 0; ?>
                    <div class="product">
                        <a href="product.php?id=<?= $product['id']; ?>">
                            <figure>
                                <img src="img/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                                <figcaption>
                                    <span class="description-short"><?= htmlspecialchars(substr($product['description'], 0, 100)); ?>...</span>
                                    <span class="description-full"><?= htmlspecialchars($product['description']); ?></span>
                                    <span class="read-more">Read More</span>
                                </figcaption>
                            </figure>
                        </a>
                        <h3><?= htmlspecialchars($product['name']); ?></h3>
                        <p class="price">₱<?= number_format($product['price'], 2); ?></p>
                        <p class="stock"><?= $isInStock ? "Stock: " . $product['stock_quantity'] : "<span style='color: red;'>Out of Stock</span>"; ?></p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const readMoreLinks = document.querySelectorAll('.read-more');
            readMoreLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const shortDescription = this.previousElementSibling.previousElementSibling;
                    const fullDescription = this.previousElementSibling;
                    if (fullDescription.style.display === 'none') {
                        shortDescription.style.display = 'none';
                        fullDescription.style.display = 'block';
                        this.textContent = 'Read Less';
                    } else {
                        shortDescription.style.display = 'block';
                        fullDescription.style.display = 'none';
                        this.textContent = 'Read More';
                    }
                });
            });
        });
    </script>
</body>
</html>

