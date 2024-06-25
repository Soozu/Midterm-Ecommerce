<?php
session_start();
include 'db.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch statistics for the dashboard
$total_sales_query = "SELECT SUM(total) as total_sales FROM orders";
$total_sales_result = $conn->query($total_sales_query);
$total_sales = $total_sales_result->fetch_assoc()['total_sales'];

$orders_today_query = "SELECT COUNT(*) as orders_today FROM orders WHERE DATE(created_at) = CURDATE()";
$orders_today_result = $conn->query($orders_today_query);
$orders_today = $orders_today_result->fetch_assoc()['orders_today'];

$total_users_query = "SELECT COUNT(*) as total_users FROM users";
$total_users_result = $conn->query($total_users_query);
$total_users = $total_users_result->fetch_assoc()['total_users'];

$total_products_query = "SELECT COUNT(*) as total_products FROM products";
$total_products_result = $conn->query($total_products_query);
$total_products = $total_products_result->fetch_assoc()['total_products'];

// Fetch weekly sales and orders data
$sales_weekly_query = "SELECT DATE(created_at) as date, SUM(total) as total_sales FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(created_at)";
$sales_weekly_result = $conn->query($sales_weekly_query);

$orders_weekly_query = "SELECT DATE(created_at) as date, COUNT(*) as total_orders FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(created_at)";
$orders_weekly_result = $conn->query($orders_weekly_query);

$sales_data = [];
$orders_data = [];
$dates = [];

while ($row = $sales_weekly_result->fetch_assoc()) {
    $sales_data[] = $row['total_sales'];
    $dates[] = $row['date'];
}

while ($row = $orders_weekly_result->fetch_assoc()) {
    $orders_data[] = $row['total_orders'];
}

// Ensure we have the same number of data points for both graphs
if (count($sales_data) !== count($orders_data)) {
    $max_count = max(count($sales_data), count($orders_data));
    $sales_data = array_pad($sales_data, $max_count, 0);
    $orders_data = array_pad($orders_data, $max_count, 0);
    $dates = array_pad($dates, $max_count, '');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-container">
        <nav class="admin-sidebar">
            <ul>
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="OrderManagement.php">Order Management</a></li>
                <li><a href="ProductManagement.php">Product Management</a></li>
                <li><a href="Categories.php">Categories</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <main class="admin-main">
            <header class="admin-header">
                <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?></h1>
                <p>Admin Dashboard</p>
            </header>
            <section class="admin-dashboard">
                <div class="dashboard-card">
                    <h3>Total Sales</h3>
                    <p>₱<?= number_format($total_sales, 2); ?></p>
                </div>
                <div class="dashboard-card">
                    <h3>Orders Today</h3>
                    <p><?= $orders_today; ?></p>
                </div>
                <div class="dashboard-card">
                    <h3>Total Users</h3>
                    <p><?= $total_users; ?></p>
                </div>
                <div class="dashboard-card">
                    <h3>Total Products</h3>
                    <p><?= $total_products; ?></p>
                </div>
                <!-- Weekly Sales Graph -->
                <div class="dashboard-card">
                    <h3>Weekly Sales</h3>
                    <canvas id="salesChart"></canvas>
                </div>
                <!-- Weekly Orders Graph -->
                <div class="dashboard-card">
                    <h3>Weekly Orders</h3>
                    <canvas id="ordersChart"></canvas>
                </div>
            </section>
        </main>
    </div>
    <script>
        // Weekly Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($dates); ?>,
                datasets: [{
                    label: 'Weekly Sales',
                    data: <?= json_encode($sales_data); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Weekly Orders Chart
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        const ordersChart = new Chart(ordersCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($dates); ?>,
                datasets: [{
                    label: 'Weekly Orders',
                    data: <?= json_encode($orders_data); ?>,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
