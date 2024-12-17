<?php
header('Content-Type: application/json');

function getKatalog()
{
    $apiUrl = "http://localhost/TST-TA/servertst/api/api.php/getKatalog";
    $response = file_get_contents($apiUrl);

    if ($response === FALSE) {
        return json_encode(['error' => 'Tidak dapat terhubung ke server gudang.']);
    }

    $katalog = json_decode($response, true);
    if (isset($katalog['error'])) {
        return json_encode(['error' => 'Error: ' . $katalog['error']]);
    }

    $result = [];
    foreach ($katalog as $item) {
        $result[] = [
            'product_name' => $item['product_name'],
            'price' => $item['price'],
            'quantity' => $item['quantity']
        ];
    }

    return json_encode($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo getKatalog();
}
