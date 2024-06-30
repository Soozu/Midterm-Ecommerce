<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Mabines</title>
    <link rel="stylesheet" href="css/header.css"> 
    <link rel="stylesheet" href="css/chat.css">
</head>
<body>
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
                    <div class="user-menu">
                        <span class="username-display"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <div class="dropdown-content">
                            <a href="Order.php">Order</a>
                            <a href="profile.php">Profile Setting</a>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a href="admin.php">Admin Page</a>
                            <?php endif; ?>
                            <a href="logout.php">Log Out</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="button-link">Log In</a>
                    <a href="register.php" class="button-link">Register</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="bottom-header">
            <nav class="navigation">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="Makeup.php">Make Up</a></li>
                    <li><a href="Skincare.php">Skin Care</a></li>
                    <li><a href="viewCart.php">My Cart</a></li>
                    <!-- More links can be added here -->
                </ul>
            </nav>
        </div>
    </header>
 <!-- Chat Support -->
 <div class="chat-container" id="chat-container">
        <div class="chat-header">
            <h2>Support Chat</h2>
            <button class="close-chat" onclick="toggleChat()">✖</button>
        </div>
        <div class="chat-box" id="chat-box">
            <div class="bot-message">Hello, how can I help you?</div>
        </div>
        <div class="chat-input">
            <input type="text" id="user-input" placeholder="Type your message...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>
    <button class="open-chat" id="open-chat" onclick="toggleChat()">Chat</button>

    <script src="scripts/chat.js"></script>
</body>
</html>
