<?php 
    header("Content-type: text/xml");   
    session_start();  

include('../../model/persistence/gridService.php');


    $result = gridService::getGroupsAdmin();
    
    
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

      for($i=0;$i<=6;$i++){
          if($i==3){
              $contenido="";
              $result4 = gridService::getNameSurnameGroupTeacher($fila['0']);
                if($result4!=FALSE){
                    if($profesor=mysql_fetch_assoc($result4)){
                        
                        $nombre=utf8_encode($profesor['nombre']);
                        $apellidos=utf8_encode($profesor['apellidos']);
                        $contenido="$nombre $apellidos ";
                        
                    }
                }
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $cell->appendChild($dom->createCDATASection($contenido));        
          }
            if($i==4){ //columna nº alumnos
            $numalumnos = "";
            $idGrupo = $fila[0];
                $result2 = gridService::getStudentsNumber($idGrupo);
                if($result2!=FALSE){
                    if($count=mysql_fetch_assoc($result2)){
                        $numalumnos=$count['total'];
                    }
                }               
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $cell->appendChild($dom->createCDATASection(utf8_encode($numalumnos)));
            }
            if($i==5){ //Columna de la imagen solicitud
                $result3 = gridService::getGroupsRequest($fila[0]);
                if($result3!=FALSE){
                     if($solicitud=mysql_fetch_assoc($result3)){
                            $cell= $row->appendChild($dom->createElement("cell"));
                            $domAtribute = $dom->createAttribute('type');
                            $domAtribute->value='img';
                            $cell->appendChild($domAtribute);
                            $contenido = ("../public/img/alert.png^^javascript:showAlert()^' id='".$cont." ");
                            $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
                    }
                    else{
                        $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                        $domAtribute = $dom->createAttribute('type');
                        $domAtribute->value='img';
                        $cell->appendChild($domAtribute);
                        $contenido = ("../public/img/yes.png' id='".$cont." ");
                        $cell->appendChild($dom->createCDATASection($contenido));
                    }
                }
            }
            if($i==6){ //Columna de la imagen eliminar
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $contenido = ("../public/img/delete.png^^javascript:deleteGroup()^' id='".$cont."");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i!=3 && $i!=4 && $i!=5 && $i!=6){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $contenido = ("$fila[$i]");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
      }
    }
 
    echo $dom->saveXML();

?> 