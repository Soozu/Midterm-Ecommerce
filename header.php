<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Mabines</title>
    <link rel="stylesheet" href="css/header.css"> <!-- Assuming you have a styles.css file -->
<!-- Start of Async Drift Code -->
<script>
"use strict";

!function() {
  var t = window.driftt = window.drift = window.driftt || [];
  if (!t.init) {
    if (t.invoked) return void (window.console && console.error && console.error("Drift snippet included twice."));
    t.invoked = !0, t.methods = [ "identify", "config", "track", "reset", "debug", "show", "ping", "page", "hide", "off", "on" ], 
    t.factory = function(e) {
      return function() {
        var n = Array.prototype.slice.call(arguments);
        return n.unshift(e), t.push(n), t;
      };
    }, t.methods.forEach(function(e) {
      t[e] = t.factory(e);
    }), t.load = function(t) {
      var e = 3e5, n = Math.ceil(new Date() / e) * e, o = document.createElement("script");
      o.type = "text/javascript", o.async = !0, o.crossorigin = "anonymous", o.src = "https://js.driftt.com/include/" + n + "/" + t + ".js";
      var i = document.getElementsByTagName("script")[0];
      i.parentNode.insertBefore(o, i);
    };
  }
}();
drift.SNIPPET_VERSION = '0.3.1';
drift.load('dc979ehmauk5');
</script>
<!-- End of Async Drift Code -->
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

</body>
</html>
