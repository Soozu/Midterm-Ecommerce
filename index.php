<?php
session_start();
include 'db.php'; // Ensure this path is correct
include 'header.php'; // Include header

// Fetch all active products from the database
$query = "SELECT * FROM products WHERE status = 'active'";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Name</title>
    <link rel="stylesheet" href="css/styles.css">
    
</head>
<body>
<main>
<!-- Main Content -->
<div class="main-content">
    <!-- Banner with slides -->
    <div class="banner">
        <div class="slide fade">
            <img src="img/item1.jpg" alt="Banner Image 1">
        </div>
        <div class="slide fade">
            <img src="img/item2.jpg" alt="Banner Image 2">
        </div>
        <div class="slide fade">
            <img src="img/item3.jpg" alt="Banner Image 3">
        </div>
        <div class="slide fade">
            <img src="img/item4.jpg" alt="Banner Image 4">
        </div>
        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>
    </div>

    <h1 align="center">Products</h1>
    <section class="products">
        <div id="products-container" class="product-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($product = $result->fetch_assoc()): ?>
                    <?php $isInStock = $product['stock_quantity'] > 0; ?>
                    <div class="product">
                        <figure>
                            <img src="img/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                            <figcaption><?= htmlspecialchars($product['description']); ?></figcaption>
                        </figure>
                        <h3><?= htmlspecialchars($product['name']); ?></h3>
                        <p class="price">$<?= number_format($product['price'], 2); ?></p>
                        <p class="stock">Stock: <?= htmlspecialchars($product['stock_quantity']); ?></p>
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
</div>

</main>

<script src="cart.js"></script> <!-- Script for cart functionality -->
<script src="slideshow.js"></script> <!-- Script for banner slide show -->
</body>
</html>
<?php include 'footer.php'; ?>
