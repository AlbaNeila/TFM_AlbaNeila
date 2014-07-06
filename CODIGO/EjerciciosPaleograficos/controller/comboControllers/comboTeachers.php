<?php   
    //PHP file to generate the XML document with the teachers discharged to load a dhtmlxcombo.
    
    header("Content-type: text/xml"); 
    session_start();  
    include('../../model/persistence/comboService.php');

    if($_REQUEST['method']=="admin"){
        $result = comboService::getTeachers();
    }
    
    $dom = new DOMDocument("1.0","UTF-8");
    $dom->formatOutput = true;
    $rows = $dom->appendChild($dom->createElement("complete"));
    $cont = 0;
    
    while ($fila = @mysql_fetch_array($result)){
        if($cont == 0){
            $domElement = $dom->createElement("option");
            $domAtribute = $dom->createAttribute('value');
            $domAtribute->value=utf8_encode($fila[2]);
            $domElement->appendChild($domAtribute);
            $row = $rows->appendChild($domElement);
            $domAtribute = $dom->createAttribute('selected');
            $domAtribute->value="selected";
            $domElement->appendChild($domAtribute);
            $row = $rows->appendChild($domElement);
            $nombre=utf8_encode($fila[0]);
            $apellidos=utf8_encode($fila[1]);
            $contenido="$nombre $apellidos ";
            $row->appendChild($dom->createCDATASection($contenido));
        }else{
            $domElement = $dom->createElement("option");
            $domAtribute = $dom->createAttribute('value');
            $domAtribute->value=utf8_encode($fila[2]);
            $domElement->appendChild($domAtribute);
            $row = $rows->appendChild($domElement);
            $nombre=utf8_encode($fila[0]);
            $apellidos=utf8_encode($fila[1]);
            $contenido="$nombre $apellidos ";
            $row->appendChild($dom->createCDATASection($contenido));
        }
        $cont++;     
    }
 
    echo $dom->saveXML();

?> 