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
    <header>
        <div class="top-header">
            <div class="logo-container">
                <img src="img/logo.webp" alt="Shop Logo" />
            </div>
            <div class="shop-name">
                  Mabine's Cosmetics  
            </div>
            <div class="user-actions">
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
        <span class="username-display"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="logout.php" class="button-link">Log Out</a>
    <?php else: ?>
        <a href="#" id="loginRegisterLink" class="button-link">Log In / Register</a>
    <?php endif; ?>
</div>

        </div>
        <div class="bottom-header">
            <nav class="navigation">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">Make Up</a></li>
                    <li><a href="#">Skin Care</a></li>
                    <li><a href="#">Favorites</a></li>
                    <li><a href="addToCart.php">My Cart</a></li>
                    <!-- More links can be added here -->
                </ul>
            </nav>
        </div>
    </header>