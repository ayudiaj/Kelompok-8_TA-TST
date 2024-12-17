<?php
$pdo = new PDO("mysql:host=localhost:3308;dbname=warehouse_db", "root", "");

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header("Location: warehouse.php");
    exit();
}
?>
