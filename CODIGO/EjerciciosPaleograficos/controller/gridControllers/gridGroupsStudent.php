<?php   
    header("Content-type: text/xml"); 
    session_start();  
    include('../../model/persistence/gridService.php');
 


    $result = gridService::getGroupsStudent($_SESSION['usuario_id']);
    
    
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
            if($i==3){ //columna profesor responsable
            $contenido = "";
            $idGroup = $fila[0];
                $teacher = gridService::getNameSurnameGroupTeacher($idGroup);
                if($teacher!=FALSE){
                    if($profesor=mysql_fetch_assoc($teacher)){
                        $contenido=$profesor['nombre'];
                        $contenido.= " " .$profesor['apellidos'];
                    }
                }               
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i==4){ //Columna de la imagen entrar
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $canAccess=gridService::canAccessGroup($_SESSION['usuario_id'], $fila[0]);
                if($access = mysql_fetch_assoc($canAccess)){
                    $contenido = ("../public/img/enter.png^^javascript:accessGroup()^' id='".$cont."");
                }else{
                    $isRequest = gridService::checkIsRequestSend($_SESSION['usuario_id'], $fila[0]);
                    if($isRequest){
                        $contenido = ("../public/img/requestaccesssent.png^^javascript:requestSent(this)^' id='".$cont."");
                    }else{
                        $contenido = ("../public/img/requestaccess.png^^javascript:requestAccess()^' id='".$cont."");
                    }
                }
                
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i!=3 && $i!=4){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $contenido = ("$fila[$i]");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
      }
    }
 
    echo $dom->saveXML();

?> 