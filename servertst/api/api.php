<?php
$host = '127.0.0.1:3308';
$username = 'root';
$password = '';
$database = 'warehouse_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

header("Content-Type: application/json");

$path = "http://localhost/servertst/api/api.php/getKatalog";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $path === 'http://localhost/servertst/api/api.php/getKatalog') {
    try {
        $stmt = $pdo->query("SELECT product_name, price, quantity FROM products");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Gagal mengambil data: " . $e->getMessage()]);
    }
} else {
    http_response_code(404);
    echo json_encode(["error" => "Endpoint tidak ditemukan"]);
}
?>