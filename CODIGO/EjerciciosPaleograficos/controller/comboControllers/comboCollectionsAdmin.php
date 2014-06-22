<?php   

    session_start();
    include('../../model/persistence/comboService.php');  
    
    header("Content-type: text/xml");

    $result = comboService::getCollectionsOfAdmin();
    
    
    $dom = new DOMDocument("1.0","UTF-8");
    $dom->formatOutput = true;
    $rows = $dom->appendChild($dom->createElement("complete"));
    $cont = 0;
    
    while ($fila = @mysql_fetch_array($result)){
        if($cont == 0){
            $domElement = $dom->createElement("option");
            $domAtribute = $dom->createAttribute('value');
            $domAtribute->value=utf8_encode($fila[1]);
            $domElement->appendChild($domAtribute);
            $row = $rows->appendChild($domElement);
            $domAtribute = $dom->createAttribute('selected');
            $domAtribute->value="selected";
            $domElement->appendChild($domAtribute);
            $row = $rows->appendChild($domElement);
            $row->appendChild($dom->createCDATASection(utf8_encode($fila[0])));
        }else{
            $domElement = $dom->createElement("option");
            $domAtribute = $dom->createAttribute('value');
            $domAtribute->value=utf8_encode($fila[1]);
            $domElement->appendChild($domAtribute);
            $row = $rows->appendChild($domElement);
            $row->appendChild($dom->createCDATASection(utf8_encode($fila[0])));
            $cont++; 
        }    
    }
 
    echo $dom->saveXML();

?> 