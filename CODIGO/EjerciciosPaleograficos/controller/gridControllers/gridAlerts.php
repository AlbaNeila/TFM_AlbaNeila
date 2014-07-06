<?php
    //PHP file to generate the XML document with the group access requests of students, to load a dhtmlxgrid.
     
    header("Content-type: text/xml");   
    session_start();  
    include('../../model/persistence/gridService.php');

    $idGrupo = $_REQUEST['idGrupo'];
    $result = gridService::getAlertsStudents($idGrupo);
    
    $dom = new DOMDocument("1.0","UTF-8");
    $dom->formatOutput = true;
    $rows = $dom->appendChild($dom->createElement("rows"));
    $cont = 0;
    
    while($fila = @mysql_fetch_array($result)){
        $domElement = $dom->createElement("row");
        $domAtribute = $dom->createAttribute('id');
        $domAtribute->value=$cont++;
        $domElement->appendChild($domAtribute);
        $row = $rows->appendChild($domElement); //añadimos <row>

      for($i=0;$i<=5;$i++){
         if($i==0){
            $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
            $domAtribute = $dom->createAttribute('id');
            $domAtribute->value=$fila[0];
            $cell->appendChild($domAtribute);
            $contenido = ("$fila[1]");
            $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
         }
        if($i==4){ //Columna del checkbox
            $cell= $row->appendChild($dom->createElement("cell"));
            $domAtribute = $dom->createAttribute('type');
            $domAtribute->value='ch';
            $cell->appendChild($domAtribute);
            $contenido = ("0");
            $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
        }
        if($i!=4 && $i!=5 && $i!=0 && $i!=1){
            $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
            $contenido = ("$fila[$i]");
            $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
        }
      }
    }
 
    echo $dom->saveXML();

?> 