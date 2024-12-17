<?php
$host = '127.0.0.1:3308';
$db = 'chat_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
        $message = $_POST['message'];

        if (!$username || !$message) {
            echo "Username atau message kosong!";
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO messages (username, message) VALUES (?, ?)");
        $stmt->execute([$username, $message]);
    } 
    else {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (!isset($data['message'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Pesan tidak ditemukan']);
            exit;
        }

        $message = $data['message'];
        $username = 'gudang'; 
        $stmt = $pdo->prepare("INSERT INTO messages (username, message) VALUES (?, ?)");
        $stmt->execute([$username, $message]);
    }
}
?>
