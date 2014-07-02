<?php  
header("Content-type: text/xml");    
     session_start();  
    include('../../model/persistence/comboService.php');

    $idColeccion =$_REQUEST['idCollection'];
   $result = comboService::getDocumentsOfCollection($idColeccion);
    
    
    $dom = new DOMDocument("1.0","UTF-8");
    $dom->formatOutput = true;
    $rows = $dom->appendChild($dom->createElement("complete"));
    $cont = 0;
    
    while ($fila = @mysql_fetch_array($result)){
        $domElement = $dom->createElement("option");
        $domAtribute = $dom->createAttribute('value');
        $domAtribute->value=utf8_encode($fila[1]);
        $domElement->appendChild($domAtribute);
        $row = $rows->appendChild($domElement);
        $row->appendChild($dom->createCDATASection(utf8_encode($fila[0])));
        $cont++;     
    }
 
    echo $dom->saveXML();

?> 