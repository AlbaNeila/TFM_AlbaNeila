<?php
    //PHP file to generate the XML document with the exercises collection of a lecturer, to load a dhtmlxgrid.
       
    header("Content-type: text/xml"); 
    session_start();  
    include('../../model/persistence/gridService.php');

    $result = gridService::getExercisesOfTeacher($_SESSION['usuario_id'], $_REQUEST['idCollection']);
    
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

      for($i=0;$i<=10;$i++){
                    if($i==2){ //Columna Documento
                $cell= $row->appendChild($dom->createElement("cell"));
                $contenido="";
                $result2 = gridService::getDocumentsNameById($fila[2]);
                if($document=mysql_fetch_assoc($result2)) {
                    $contenido=utf8_encode($document['nombre']);
                }  
                $cell->appendChild($dom->createCDATASection($contenido));
            }
          if($i==3){ //Select dificultad realización
                $cell= $row->appendChild($dom->createElement("cell"));
                $tips=$fila[3];
                if($tips==0){
                  $options = "<option value='0' selected>Fácil</option>
                                <option value='1'>Medio</option>
                                <option value='2'>Difícil</option>";
                }
                if($tips==1){
                   $options = "<option value='0'>Fácil</option>
                        <option value='1' selected>Medio</option>
                        <option value='2'>Difícil</option>";
                }
                if($tips==2){
                   $options = "<option value='0'>Fácil</option>
                        <option value='1'>Medio</option>
                        <option value='2' selected>Difícil</option>";
                }
                
                $contenido="<select id='tips' name='tips' onchange='updateTips(this)'>$options</select>";
                $cell->appendChild($dom->createCDATASection($contenido));
            }
           if($i==4){ //Objetivo
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $target=$fila[4];
                if($target=='% palabras acertadas'){
                  $options = "<option value='0' selected>% palabras acertadas</option>
                              <option value='1'>Nº máximo de fallos</option>";
                }
                else{
                   $options = "<option value='0'>% palabras acertadas</option>
                               <option value='1' selected>Nº máximo de fallos</option>";
                }
                $contenido = "<select id='target$cont' name='target' onchange='updateTarget(this)'>$options</select> <input id='target$cont' size='2' type='text' value='$fila[5]' onblur='updateValueTarget(this)'/>";
                $cell->appendChild($dom->createCDATASection($contenido));
           } 
            if($i==6){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $correction=$fila[6];
                if($correction==0){
                    $options="<option value='0' selected>Corregir al final</option>
                              <option value='1'>Corregir paso a paso</option>";
                }else{
                    $options="<option value='0' selected>Corregir al final</option>
                              <option value='1'selected>Corregir paso a paso</option>";
                }
                $contenido="<select id='correction' name='correction' onchange='updateCorrectionMode(this)'>$options</select>";
                $cell->appendChild($dom->createCDATASection($contenido));
            } 
           if($i==10){ //Columna de la imagen eliminar
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $contenido = ("../public/img/delete.png^^javascript:deleteEj()^' id='".$cont."");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
           if($i==7){ 
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $contenido = (" ../public/img/groups.png^^javascript:consultGroups()^' id='".$fila[0]."");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
           if($i==8){ //Columna de la imagen ordenar
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $contenido = (" ../public/img/up.png^^javascript:upEj()^' ");
                $cell->appendChild($dom->createCDATASection(($contenido)));
            }
             if($i==9){ //Columna de la imagen ordenar
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $contenido = (" ../public/img/down.png^^javascript:downEj()^' ");
                $cell->appendChild($dom->createCDATASection(($contenido)));
            }
            if($i==0 || $i==1 ){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $domAtribute = $dom->createAttribute('idEj');
                $domAtribute->value=$fila[0];
                $cell->appendChild($domAtribute);
                $domAtribute = $dom->createAttribute('orden');
                $domAtribute->value=$fila[7];
                $cell->appendChild($domAtribute);
                $domAtribute = $dom->createAttribute('idCol');
                $domAtribute->value=$_REQUEST['idCollection'];
                $cell->appendChild($domAtribute);
                $contenido = ("$fila[$i]");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
      }
    }
 
    echo $dom->saveXML();

?> 