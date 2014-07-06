<?php   
    //PHP file to generate the XML document with the groups of a lecturer, to load a dhtmlxgrid.
    
    header("Content-type: text/xml"); 
    session_start();  
    include('../../model/persistence/gridService.php');

    $idSearched=$_REQUEST['idSearched'];
    $result = gridService::getGroupsTeacher($idSearched);
  
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

      for($i=0;$i<=1;$i++){          
            if($i!=0){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $domAtribute = $dom->createAttribute('idGroup');
                $domAtribute->value=$fila[0];
                $cell->appendChild($domAtribute);
                $contenido = ("$fila[$i]");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
      }
    }
 
    echo $dom->saveXML();

?> 