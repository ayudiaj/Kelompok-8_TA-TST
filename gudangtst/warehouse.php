<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Sistem Gudang</h1>
        <div class="text-end mb-3">
            <a href="add_product.php" class="btn btn-primary">Tambah Produk</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Koneksi ke database
                $pdo = new PDO("mysql:host=localhost:3308;dbname=warehouse_db", "root", "");

                // Mengambil data produk
                $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
                while ($row = $stmt->fetch()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['product_name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>Rp " . number_format($row['price'], 2, ',', '.') . "</td>
                        <td>
                            <a href='edit_product.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_product.php?id={$row['id']}' class='btn btn-danger btn-sm'>Hapus</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
