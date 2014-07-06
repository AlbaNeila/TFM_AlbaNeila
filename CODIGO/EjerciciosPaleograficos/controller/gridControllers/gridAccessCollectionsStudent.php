<?php  
    //PHP file to generate the XML document with the collections of a group of a specific student, to load a dhtmlxgrid.
    
    header("Content-type: text/xml");  
    session_start();  
    include('../../model/persistence/gridService.php');
    
    $result = gridService::getCollectionsByUserAndGroup($_SESSION['usuario_id'], $_REQUEST['idGroup']);
    
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
          if($i==3){ //columna documentos
           $numdocumentos = "";
            $idColeccion = $fila[0];
                $result2 = gridService::getCountCollections($idColeccion);
                if($result2!=FALSE){
                    if($count=mysql_fetch_assoc($result2)){
                        $numdocumentos=$count['total'];
                    }
                }               
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $cell->appendChild($dom->createCDATASection(utf8_encode($numdocumentos)));
            }
            if($i==4){ //Columna ejercicios
               $numdocumentos = "";
                $idColeccion = $fila[0];
                $result2 = gridService::getCountExercises($_SESSION['usuario_id'], $idColeccion);
                if($result2!=FALSE){
                    if($count=mysql_fetch_assoc($result2)){
                        $numdocumentos=$count['total'];
                    }
                }               
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $cell->appendChild($dom->createCDATASection(utf8_encode($numdocumentos)));
            }
            if($i==5){ //Columna de la imagen entrar
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $contenido = ("../public/img/enter.png^^javascript:accessCollection()^'");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i!=3 && $i!=4 && $i!=5){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $contenido = ("$fila[$i]");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
      }
    }
 
    echo $dom->saveXML();

?> 