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
        <h1>Products</h1>
        <div id="products-container" class="product-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($product = $result->fetch_assoc()): ?>
                    <?php $isInStock = $product['stock_quantity'] > 0; ?>
                    <div class="product">
                        <figure>
                            <img src="img/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                            <figcaption>
                                <span class="description-short"><?= htmlspecialchars(substr($product['description'], 0, 100)); ?>...</span>
                                <span class="description-full"><?= htmlspecialchars($product['description']); ?></span>
                                <span class="read-more">Read More</span>
                            </figcaption>
                        </figure>
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
</div>

</main>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            const readMoreLinks = document.querySelectorAll('.read-more');
            readMoreLinks.forEach(link => {
                link.addEventListener('click', function() {
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

<script src="cart.js"></script> <!-- Script for cart functionality -->
<script src="slideshow.js"></script> <!-- Script for banner slide show -->
</body>
</html>
<?php include 'footer.php'; ?>
