<?php
session_start();

include 'db.php'; // Adjust the path as needed to point to your actual database connection file

if (isset($_SESSION['login_error'])) {
    $loginError = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
} else {
    $loginError = '';
}

$sql = "SELECT * FROM products"; // Adjust according to your actual database schema
$result = $conn->query($sql);
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
<div class="loader" id="loader"></div>

<?php include 'header.php'; ?>

<!-- Login Modal -->
<div id="loginModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Login</h2>
    <!-- Display the login error message if set -->
    <?php if (isset($_SESSION['login_error'])): ?>
    <div class="error-message">
        <?php echo htmlspecialchars($_SESSION['login_error']); ?>
    </div>
    <?php unset($_SESSION['login_error']); // Clear the error message ?>
<?php endif; ?>

    <form action="login.php" method="post">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
      <p>Don't have an account? <a href="#" id="switchToRegister">Register now</a></p>
    </form>
  </div>
</div>


<!-- Register Modal -->
<div id="registerModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Register</h2>
    <form action="register.php" method="post">
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit" name="register">Register</button>
      <p>Already have an account? <a href="#" id="switchToLogin">Login here</a></p>
    </form>
  </div>
</div>



<main>
    <div class="banner">
        <div class="slide fade">
            <img src="img/king1.jpeg" alt="Banner Image 1">
        </div>
        <div class="slide fade">
            <img src="img/product4.jpg" alt="Banner Image 2">
        </div>
        <div class="slide fade">
            <img src="img/product3.jpg" alt="Banner Image 3">
        </div>
        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>
    </div>
    
    <section class="products">
    <div id="products-container" class="product-grid">
        <?php while($product = $result->fetch_assoc()): ?>
            <div class="product">
                <!-- Enclose the product image in a figure tag for semantic HTML -->
                <figure>
                    <img src="img/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <figcaption><?php echo htmlspecialchars($product['description']); ?></figcaption>
                </figure>
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p class="price">$<?php echo number_format((float)$product['price'], 2); ?></p>
                <!-- Use form for Add to Cart to prepare for a future enhancement where you might want to specify quantity -->
                <form action="addToCart.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="add-to-cart">Add to Cart</button>
                </form>
                <!-- Direct Buy Now functionality can be handled similarly through a form -->
                <form action="buyNow.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="buy-now">Buy Now</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</section>
</main>
<?php include 'footer.php'; ?>
<script>
var loginError = <?php echo isset($_SESSION['show_login_modal']) ? 'true' : 'false'; ?>;
</script>
<script src="scripts/slideshow.js"></script>
<script src="scripts/modal.js"></script>
<script src="scripts/cart.js"></script>
</body>
</html>
