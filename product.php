<?php
session_start();
include 'db.php';  // Ensure the path is correct

// Check if the product ID is present
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // Prepare a statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the product
    if ($product = $result->fetch_assoc()) {
        include 'header.php';  // Include the header
?>
        <div class="product-detail">
    <img src="img/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
    <div>
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <p class="price">Price: ₱<?php echo number_format($product['price'], 2); ?></p>
        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        <a href="addToCart.php?product_id=<?= $product['id']; ?>" class="button">Add to Cart</a>
        <a href="addToCart.php?product_id=<?= $product['id']; ?>&checkout=true" class="button">Buy Now</a>
    </div>
</div>
<?php
        include 'footer.php';  // Include the footer
    } else {
        echo "<p>Product not found.</p>";
    }
} else {
    echo "<p>Invalid product ID.</p>";
}
?>
<link rel="stylesheet" href="css/product.css">
<script>
function addToCart(productId) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = 'AddToCart.php';

    var hiddenField = document.createElement('input');
    hiddenField.type = 'hidden';
    hiddenField.name = 'product_id';
    hiddenField.value = productId;
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
}
</script>