<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/dashboard-style.css">
</head>
<body>
    <header class="dashboard-header">
        <h1>Admin Dashboard</h1>
        <!-- Top bar content like logout, profile, settings -->
    </header>

    <aside class="dashboard-sidebar">
        <!-- Sidebar content -->
        <nav>
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="sellers.php">Sellers</a></li>
                <li><a href="customers.php">Customers</a></li>
                <li><a href="product.php">Products</a></li>
                <li><a href="analytics.php">Analytics</a></li>
            </ul>
        </nav>
    </aside>

    <main class="dashboard-content">
        <!-- Content based on the selection from the sidebar -->
        <section class="dashboard-section">
            <!-- Details for each section, dynamically included based on selection -->
        </section>
    </main>

    <footer class="dashboard-footer">
        &copy; <?php echo date("Y"); ?> Your Company. All rights reserved.
    </footer>

    <script src="scripts/dashboard-script.js"></script>
</body>
</html>
