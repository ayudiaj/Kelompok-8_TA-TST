<?php
class Gudang
{
    private $host = '127.0.0.1';
    private $port = '3308';
    private $username = 'root';
    private $password = '';
    private $database = 'warehouse_db';

    /**
     * Koneksi ke database
     */
    private function connectDB()
    {
        $conn = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);
        if ($conn->connect_error) {
            throw new Exception('Koneksi database gagal: ' . $conn->connect_error);
        }
        return $conn;
    }

    /**
     * Mengambil katalog barang
     * @return array
     */
    public function getKatalogBarang()
    {
        $conn = $this->connectDB();

        $sql = "SELECT id, product_name, quantity, price FROM products ORDER BY created_at DESC";
        $result = $conn->query($sql);

        $products = [];
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $products[] = $row;
                }
            }
        } else {
            throw new Exception("Error dalam query database: " . $conn->error);
        }

        $conn->close();
        return $products;
    }

    /**
     * Menghapus katalog barang berdasarkan ID
     * @param int $id
     * @return string
     */
    public function hapusKatalogBarang($id, $quantity)
{
    $conn = $this->connectDB();

    if ($quantity <= 0) {
        throw new Exception("Jumlah pengurangan harus lebih besar dari 0.");
    }

    $sql = "UPDATE products SET quantity = quantity - ? WHERE id = ? AND quantity >= ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error dalam query: " . $conn->error);
    }

    $stmt->bind_param("iii", $quantity, $id, $quantity);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $message = "Produk dengan ID $id berhasil dikurangi stok sebanyak $quantity.";
    } else {
        $message = "Produk dengan ID $id gagal dikurangi. Periksa stok.";
    }

    $stmt->close();
    $conn->close();

    return $message;
}

}
