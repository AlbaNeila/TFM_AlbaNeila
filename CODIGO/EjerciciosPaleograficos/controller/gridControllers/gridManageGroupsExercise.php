<?php  
    //PHP file to generate the XML document to manage the group access to the exercises of a collection, to load a dhtmlxgrid.
      
    header("Content-type: text/xml");
    session_start();  
    include('../../model/persistence/gridService.php');

    $result = gridService::getGroupIdAndNameByCollectionId($_REQUEST['idCollection']);
    $result2 = gridService::getGroupIdFromGroupExerciseCollection($_REQUEST['idSearched']);

    $groups = array();
    while($row = mysql_fetch_array($result2))
    {
        $groups[] = $row['idGrupo'];
    }
    
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

      for($i=0;$i<=2;$i++){
          $flag = false;
          if(in_array($fila[0],$groups)){
              $flag = true;
          }
            if($i==2){ //checkbox
                if($flag){
                    $contenido = '1';
                }
                else{
                    $contenido = '0';
                }
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
                
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='ch';
                $cell->appendChild($domAtribute);
                $domAtribute = $dom->createAttribute('idEj');
                $domAtribute->value=$fila[0];
                $cell->appendChild($domAtribute);

                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i!=0 && $i!=2){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $domAtribute = $dom->createAttribute('idEj');
                $domAtribute->value=$fila[0];
                $cell->appendChild($domAtribute);
                $contenido = ("$fila[$i]");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
      }
    }
 
    echo $dom->saveXML();

?> 