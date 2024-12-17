<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('form').addEventListener('submit', function(event) {
                const productName = document.getElementById('product_name').value.trim();
                const quantity = parseInt(document.getElementById('quantity').value, 10);
                const price = parseFloat(document.getElementById('price').value);

                if (!productName || productName.length > 100) {
                    alert('Nama produk tidak valid! Maksimal 100 karakter.');
                    event.preventDefault();
                } else if (isNaN(quantity) || quantity <= 0) {
                    alert('Jumlah harus berupa angka positif.');
                    event.preventDefault();
                } else if (isNaN(price) || price <= 0) {
                    alert('Harga harus berupa angka positif.');
                    event.preventDefault();
                }
            });
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Tambah Produk</h1>
        <form method="POST" action="">
            <?php
            session_start();
            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            ?>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="mb-3">
                <label for="product_name" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required maxlength="100">
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Jumlah</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Harga</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="warehouse.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                throw new Exception('CSRF token tidak valid!');
            }

            $pdo = new PDO("mysql:host=localhost:3308;dbname=warehouse_db", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $product_name = htmlspecialchars(strip_tags($_POST['product_name']));
            $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
            $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);

            if (!$product_name || !$quantity || !$price) {
                throw new Exception('Input tidak valid!');
            }

            $stmt = $pdo->prepare("INSERT INTO products (product_name, quantity, price) VALUES (?, ?, ?)");
            $stmt->execute([$product_name, $quantity, $price]);

            $chat_url = 'http://localhost/tst-ta/chattst/send_message.php';  
            $data = [
                'message' => "Dapatkan produk terbaru dari kami $product_name dengan harga spesial Rp $price. BURUAN stock cuma $quantity "
            ];
            $options = [
                'http' => [
                    'header' => "Content-Type: application/json\r\n",
                    'method' => 'POST',
                    'content' => json_encode($data),
                ]
            ];
            $context = stream_context_create($options);
            $response = file_get_contents($chat_url, false, $context);

            if ($response === FALSE) {
                error_log("Gagal mengirim notifikasi ke sistem chat.");
            }

            header("Location: warehouse.php");
            exit();
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Terjadi kesalahan: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
    ?>
</body>
</html>
