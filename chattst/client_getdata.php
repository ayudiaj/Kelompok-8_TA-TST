<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$wsdl = 'http://localhost/TST-TA/servertst/server.php?wsdl';

try {
     if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $client = new SoapClient($wsdl);

        $katalog = $client->getKatalogBarang();
    
        header('Content-Type: application/json');
        echo json_encode($katalog, JSON_PRETTY_PRINT);
     }
    
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
