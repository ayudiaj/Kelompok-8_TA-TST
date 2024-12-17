<?php
require_once('Gudang.php');

$wsdlFile = 'gudang.wsdl';
if (!file_exists($wsdlFile)) {
    die('Error: File WSDL tidak ditemukan. Pastikan file gudang.wsdl sudah di-generate.');
}

try {
    $server = new SoapServer($wsdlFile);
} catch (SoapFault $e) {
    die('Error saat membuat instance SoapServer: ' . $e->getMessage());
}

$server->setClass('Gudang');
try {
    $server->handle();
} catch (Exception $e) {
    echo 'Error saat memproses request: ' . $e->getMessage();
}
?>