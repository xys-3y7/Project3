<?php
require 'db.php';

$name = $description = $price = $stock = $image = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_POST['image_url'];

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = uniqid() . "_" . basename($_FILES["image_file"]["name"]);
        $targetFile = $targetDir . $fileName;
        move_uploaded_file($_FILES["image_file"]["tmp_name"], $targetFile);
        $image = $targetFile;
    }

    if ($name && $price && $stock) {
        $stmt = $pdo->prepare("INSERT INTO products (image, name, description, price, stock) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$image, $name, $description, $price, $stock]);
        header("Location: index.php");
        exit;
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Add New Product</h2>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Image URL</label>
            <input type="url" name="image_url" class="form-control">
        </div>
        <div class="mb-3">
            <label>Or Upload Image</label>
            <input type="file" name="image_file" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Create Product</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
