<?php
require 'db.php';
if (!isset($_GET['id'])) die("Product not found");

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) die("Product not found");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_POST['image_url'] ?: $product['image'];

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = uniqid() . "_" . basename($_FILES["image_file"]["name"]);
        $targetFile = $targetDir . $fileName;
        move_uploaded_file($_FILES["image_file"]["tmp_name"], $targetFile);
        $image = $targetFile;
    }

    $stmt = $pdo->prepare("UPDATE products SET image=?, name=?, description=?, price=?, stock=? WHERE id=?");
    $stmt->execute([$image, $name, $description, $price, $stock, $id]);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Edit Product</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Image URL</label>
            <input type="url" name="image_url" class="form-control" value="<?= htmlspecialchars($product['image']) ?>">
        </div>
        <div class="mb-3">
            <label>Or Upload Image</label>
            <input type="file" name="image_file" class="form-control">
            <img src="<?= htmlspecialchars($product['image']) ?>" width="120" class="mt-2 rounded">
        </div>
        <button type="submit" class="btn btn-success">Update Product</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
