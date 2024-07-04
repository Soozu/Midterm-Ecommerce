<?php
session_start();
include 'db.php';
include 'header.php';

// Fetch 4 random Makeup products
$makeup_query = "SELECT products.*, COALESCE(SUM(order_items.quantity), 0) AS sold, COALESCE(AVG(ratings.rating), 0) AS rating
                 FROM products
                 LEFT JOIN order_items ON products.id = order_items.product_id
                 LEFT JOIN ratings ON products.id = ratings.product_id
                 WHERE category_id = 1
                 GROUP BY products.id
                 ORDER BY RAND()
                 LIMIT 4";
$makeup_result = $conn->query($makeup_query);
if (!$makeup_result) {
    die("Makeup query failed: " . $conn->error);
}

// Fetch 4 random Skincare products
$skincare_query = "SELECT products.*, COALESCE(SUM(order_items.quantity), 0) AS sold, COALESCE(AVG(ratings.rating), 0) AS rating
                   FROM products
                   LEFT JOIN order_items ON products.id = order_items.product_id
                   LEFT JOIN ratings ON products.id = ratings.product_id
                   WHERE category_id = 2
                   GROUP BY products.id
                   ORDER BY RAND()
                   LIMIT 4";
$skincare_result = $conn->query($skincare_query);
if (!$skincare_result) {
    die("Skincare query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" href="css/index.css">

</head>
<body>
<div class="slideshow-container">

<div class="mySlides fade">
    <img src="img/Brushes(product1).jpeg" style="width:100%; height:400px;">
</div>

<div class="mySlides fade">
    <img src="img/Lipstick(product7).jpeg" style="width:100%; height:400px;">
</div>

<div class="mySlides fade">
    <img src="img/MilkyBleachingWhippedCream(product5).jpeg" style="width:100%; height:400px;">
</div>

<!-- Next and previous buttons -->
<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
<a class="next" onclick="plusSlides(1)">&#10095;</a>

</div>
    <br>

    <!-- The dots/circles -->
    <div style="text-align:center">
        <span class="dot" onclick="currentSlide(1)"></span> 
        <span class="dot" onclick="currentSlide(2)"></span> 
        <span class="dot" onclick="currentSlide(3)"></span> 
    </div>

    <div class="products">
        <h1>Makeup</h1>
        <div class="product-grid">
            <?php while ($product = $makeup_result->fetch_assoc()): ?>
                <div class="product">
                    <a href="product.php?id=<?= $product['id'] ?>">
                        <img src="img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    </a>
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <div class="price">₱<?= number_format($product['price'], 2) ?></div>
                    <div class="rating">
                        <?php
                        $rating = round($product['rating']);
                        for ($i = 0; $i < 5; $i++) {
                            if ($i < $rating) {
                                echo '<span class="star filled">★</span>';
                            } else {
                                echo '<span class="star">☆</span>';
                            }
                        }
                        ?>
                        <span class="sold">Sold: <?= $product['sold'] ?></span>
                        <span class="stock">Stock: <?= $product['stock_quantity'] ?></span>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="products">
        <h1>Skincare</h1>
        <div class="product-grid">
            <?php while ($product = $skincare_result->fetch_assoc()): ?>
                <div class="product">
                    <a href="product.php?id=<?= $product['id'] ?>">
                        <img src="img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    </a>
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <div class="price">₱<?= number_format($product['price'], 2) ?></div>
                    <div class="rating">
                        <?php
                        $rating = round($product['rating']);
                        for ($i = 0; $i < 5; $i++) {
                            if ($i < $rating) {
                                echo '<span class="star filled">★</span>';
                            } else {
                                echo '<span class="star">☆</span>';
                            }
                        }
                        ?>
                        <span class="sold">Sold: <?= $product['sold'] ?></span>
                        <span class="stock">Stock: <?= $product['stock_quantity'] ?></span>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";  
            }
            slideIndex++;
            if (slideIndex > slides.length) {slideIndex = 1}    
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex-1].style.display = "block";  
            dots[slideIndex-1].className += " active";
            setTimeout(showSlides, 2000); // Change image every 2 seconds
        }

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }
    </script>
</body>
<?php
include 'footer.php';
?>

</html>
