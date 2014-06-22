<?php    
    session_start();  

    include('../../model/persistence/gridService.php');
    
    $result = gridService::getExercisesOfStudent($_SESSION['usuario_id'], $_REQUEST['idCollection']);
    
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
        $sup="";
        if($cont==1){
            $resultPrimeraVuelta = gridService::getSuperado($_SESSION['usuario_id'], $fila[0]);
            if($superado=mysql_fetch_assoc($resultPrimeraVuelta)){
                $superado=$superado['superado'];
                if($superado==0){
                    $sup=true;
                }else{
                    $sup=false;
                }
            }else{
                $primero = gridService::insertUsuarioEjercicioSuperado($_SESSION['usuario_id'], $fila[0]);
                $sup=true; 
            }
        }
        
      for($i=0;$i<=4;$i++){
        $result2 = gridService::getSuperado($_SESSION['usuario_id'], $fila[0]);
        if($i==2){ //Columna Documento
                $cell= $row->appendChild($dom->createElement("cell"));
                $contenido="";
                $result2 = gridService::getDocumentsNameById($fila[2]);
                if($document=mysql_fetch_assoc($result2)) {
                    $contenido=utf8_encode($document['nombre']);
                }  
                $cell->appendChild($dom->createCDATASection($contenido));
            }
        if($i==3){ //Columna Superado
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                if($cont==1){
                    if($sup){
                        $contenido = (" ../public/img/no.png'");
                    }else{
                        $contenido = (" ../public/img/yes.png'");
                    }
                }else{
                    if(!$superado=mysql_fetch_assoc($result2)) { //Si no hay filas, es que el ejercicio todavía esta bloqueado        
                        $contenido = (" ../public/img/no.png'");
                    }else{//Si hay filas, puede estar superado(superado=1) o no superado(superado=0)
                        if($superado['superado']==0){
                            $contenido = (" ../public/img/no.png'");
                        }else{
                           $contenido = (" ../public/img/yes.png'"); 
                        }
                    }
                }  
                $cell->appendChild($dom->createCDATASection($contenido));
            }
            if($i==4){ //Columna Ejercicio
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $doc=$fila[2];
                if($cont==1){
                    if($sup){
                        $contenido = (" ../public/img/enter.png^^javascript:doEj($doc)^' ");
                    }else{
                        $contenido = (" ../public/img/enter.png^^javascript:accessEj($doc)^' ");
                    }
                }else{
                    if(!$superado=mysql_fetch_assoc($result2)) { //Si no hay filas, es que el ejercicio todavía esta bloqueado        
                        $contenido = (" ../public/img/lock.png^^javascript:lockEj($doc)^' ");
                    }else{//Si hay filas, puede estar superado(superado=1) o no superado(superado=0)
                        if($superado['superado']==0){
                            $contenido = (" ../public/img/enter.png^^javascript:doEj($doc)^'");
                        }else{
                            $contenido = (" ../public/img/enter.png^^javascript:accessEj($doc)^'");
                        }
                    }
                }  
                $cell->appendChild($dom->createCDATASection($contenido));
            }
        if($i==1){
            $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
            $domAtribute = $dom->createAttribute('idEj');
            $domAtribute->value=$fila[0];
            $cell->appendChild($domAtribute);
            $domAtribute = $dom->createAttribute('orden');
            $domAtribute->value=$fila[7];
            $cell->appendChild($domAtribute);
            
            $result3 = gridService::getDocumentTranscriptionById($fila[2]);
            if($transc=mysql_fetch_assoc($result3)) {
                $transcripcion=utf8_encode($transc['transcripcion']);
            }  
            $domAtribute = $dom->createAttribute('transc');
            $domAtribute->value=$transcripcion;
            $cell->appendChild($domAtribute);
            $contenido = ("$fila[$i]");
            $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
        }
      }
    }
 
    echo $dom->saveXML();

?> 