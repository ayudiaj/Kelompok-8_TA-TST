<?php

$wsdl = 'http://localhost/TST-TA/servertst/gudang.wsdl';

try {
    $client = new SoapClient($wsdl);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'reduce_stock' && isset($_POST['products'])) {
            $products = json_decode($_POST['products'], true);
            
            if (!is_array($products)) {
                throw new Exception("Invalid product data format.");
            }

            $responses = [];
            foreach ($products as $product) {
                if (isset($product['id'], $product['quantity'])) {
                    $id = intval($product['id']);
                    $quantity = intval($product['quantity']);
                    
                    $response = $client->hapusKatalogBarang($id, $quantity);
                    $responses[] = [
                        'id' => $id,
                        'status' => $response
                    ];
                }
            }

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Stok berhasil dikurangi',
                'data' => $responses
            ], JSON_PRETTY_PRINT);
            exit;
        }
    }

    $katalog = $client->getKatalogBarang();

    header('Content-Type: application/json');
    echo json_encode($katalog, JSON_PRETTY_PRINT);

} catch (SoapFault $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
    ], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'error' => true,
        'message' => 'Unexpected error: ' . $e->getMessage(),
    ], JSON_PRETTY_PRINT);
}
?>
    