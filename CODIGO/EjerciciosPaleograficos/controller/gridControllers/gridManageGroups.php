<?php    
    session_start();  

    include('../../model/persistence/gridService.php');

    $idSearched = $_REQUEST['idSearched'];
    if($_REQUEST['method'] == 'student'){
        $result = gridService::getGroupsAdmin();
        $result2 = gridService::getGroupIdByUser($idSearched);
    }
    if($_REQUEST['method'] == 'collectionAdmin'){
        $result =  gridService::getGroupsAdmin();
        $result2 = gridService::getGroupByCollectionId($idSearched);
    }
   if($_REQUEST['method'] == 'collection'){
        $result = gridService::getGroupsTeacher($_SESSION['usuario_id']);
        $result2 =gridService::getGroupByCollectionId($idSearched);
    }

    
    $grupos = array();
    while($row = mysql_fetch_array($result2))
        {
            $grupos[] = $row['idGrupo'];
        }
    
    header("Content-type: text/xml");
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

      for($i=0;$i<=4;$i++){
          $flag = false;
          if(in_array($fila[0],$grupos)){
              $flag = true;
          }
            if($i==3){ //Radiobutton aceptar
                $cell= $row->appendChild($dom->createElement("cell"));
                if($flag){
                    $contenido = ("<input type='radio' name='radio$cont' checked='checked'></input>");
                }
                else{
                    $contenido = ("<input type='radio' name='radio$cont'></input>");
                }
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i==4){ //Radiobutton rechazar
                $cell= $row->appendChild($dom->createElement("cell"));
                if(!$flag){
                    $contenido = ("<input type='radio' name='radio$cont' checked='checked'></input>");
                }
                else{
                    $contenido = ("<input type='radio' name='radio$cont'></input>");
                }
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i!=0 && $i!=4 && $i!=3){
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