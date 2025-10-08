<?php
require 'db.php';
if (!isset($_GET['id'])) die("Product not found");

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if ($product) {
    if (file_exists($product['image']) && !filter_var($product['image'], FILTER_VALIDATE_URL)) {
        unlink($product['image']);
    }
    $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$id]);
}
header("Location: index.php");
exit;
?>
