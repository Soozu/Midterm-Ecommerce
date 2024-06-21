<?php
session_start();

if (isset($_POST['submit'])) {
    include 'db.php';

    $name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Handle file upload securely
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $temp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $upload_dir = 'img/';

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($temp_name);

        if (in_array($file_type, $allowed_types) && $_FILES['image']['size'] <= 2000000) {
            $image_path = $upload_dir . $image_name;
            if (move_uploaded_file($temp_name, $image_path)) {
                $image = $image_name;
            }
        }
    }

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
