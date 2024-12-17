<?php

require "vendor/autoload.php";
require "Gudang.php";

use PHP2WSDL\PHPClass2WSDL;

$gen = new PHPClass2WSDL("Gudang", "http://localhost/TST-TA/servertst/server.php");

// Generate file WSDL
$gen->generateWSDL();
file_put_contents("gudang.wsdl", $gen->dump());
echo "WSDL file generated as gudang.wsdl";
?>
