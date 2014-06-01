<?php    
    session_start();  
    require_once("../../lib/dhtmlxConnector_php/codebase/grid_connector.php");
    //Configuración Base de Datos
    define("BD", "EJPALEO");
    define("HOST", "localhost");
    define("USER", "root");
    define("PASSWORD", "root");
    
    //conectamos y seleccionamos db 
    $connection = mysql_connect(HOST,USER,PASSWORD) or die('Error: Imposible conectar a la base de datos del servidor.');
    mysql_select_db(BD) or die('Error: Imposible seleccionar la base de datos.');

    $gridConn = new GridConnector($connection,"MySQL");
    $gridConn->dynamic_loading(20);

    $result = mysql_query("SELECT ejercicio.idEjercicio,ejercicio.nombre,ejercicio.idDocumento,ejercicio.idDificultad, ejercicio.tipo_objetivo, ejercicio.valor_objetivo,ejercicio.comprobarTranscripcion FROM ejercicio");
    
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

      for($i=0;$i<=9;$i++){
          if($i==2){ //Columna Documento
                $cell= $row->appendChild($dom->createElement("cell"));
                $contenido="";
                $result2 = mysql_query("SELECT documento.nombre FROM documento WHERE documento.idDocumento = '$fila[2]'");
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
                $contenido = "<select id='target$cont' name='target' onchange='updateTarget(this)'>$options</select> <input id='target$cont' size='1' type='text' value='$fila[5]'/>";
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
           if($i==7){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $contenido="";
                $result3 = mysql_query("SELECT coleccion.nombre,coleccion.idColeccion FROM coleccion,grupo_ejercicio_coleccion WHERE grupo_ejercicio_coleccion.idEjercicio = '$fila[0]' AND grupo_ejercicio_coleccion.idColeccion=coleccion.idColeccion");
                if($collection=mysql_fetch_assoc($result3)) {
                    $contenido=utf8_encode($collection['nombre']);
                } 
                $domAtribute = $dom->createAttribute('idCol');
                $domAtribute->value=$collection['idColeccion'];
                $cell->appendChild($domAtribute); 
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
           } 
           if($i==9){ //Columna de la imagen eliminar
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $contenido = ("../public/img/delete.png^^javascript:deleteEj()^' id='".$cont."");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
           if($i==8){ 
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $contenido = (" ../public/img/groups.png^^javascript:consultGroups()^' id='".$fila[0]."");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i==0 || $i==1 ){
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