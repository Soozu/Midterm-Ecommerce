<?php
session_start();

if (isset($_POST['submit'])) {
    include 'db.php'; // Your database connection file

    $name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // For the image, handle the file upload process securely
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $temp_name = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $upload_dir = 'img/';

        // Security improvement: Check the file type and size here

        $image_path = $upload_dir . $image_name;
        if (move_uploaded_file($temp_name, $image_path)) {
            $image = $image_name;
        }
    }

    // Using prepared statement to prevent SQL Injection
    $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $price, $description, $image);

    if ($stmt->execute()) {
        $_SESSION['product_added'] = "Product added successfully!";
        header('Location: product.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
