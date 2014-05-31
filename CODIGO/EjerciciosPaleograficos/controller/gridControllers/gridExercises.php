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
           if($i==4){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $domAtribute = $dom->createAttribute('idDoc');
                $domAtribute->value=$fila[0];
                $cell->appendChild($domAtribute);
                $contenido = ("$fila[$i] $fila[5] ");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
           } 
           if($i==7){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $contenido="";
                $result3 = mysql_query("SELECT coleccion.nombre FROM coleccion,grupo_ejercicio_coleccion WHERE grupo_ejercicio_coleccion.idEjercicio = '$fila[0]' AND grupo_ejercicio_coleccion.idColeccion=coleccion.idColeccion");
                if($collection=mysql_fetch_assoc($result3)) {
                    $contenido=utf8_encode($collection['nombre']);
                }  
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
           } 
           if($i==6){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $domAtribute = $dom->createAttribute('idDoc');
                $domAtribute->value=$fila[0];
                $cell->appendChild($domAtribute);
                $contenido = ("$fila[6] ");
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
           if($i==8){ //Columna de la imagen modificar ficheros
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
               
                $contenido = (" ../public/img/groups.png^^javascript:consultGroups()^' id='".$fila[0]."");

                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i!=2 && $i!=4 && $i!=5 && $i!=6  && $i!=7 && $i!=8 && $i!=9 ){
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