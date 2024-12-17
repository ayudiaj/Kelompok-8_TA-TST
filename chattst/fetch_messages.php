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

$stmt = $pdo->query("SELECT * FROM messages ORDER BY timestamp ASC");

while ($row = $stmt->fetch()) {
    echo "<p><strong>{$row['username']}:</strong> {$row['message']} <small><i>{$row['timestamp']}</i></small></p>";
}

?>


