<?php    
    session_start();  
    require_once("../lib/dhtmlxConnector_php/codebase/grid_connector.php");
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

    $result = mysql_query("SELECT grupo.idGrupo,grupo.nombre,grupo.descripcion FROM grupo,usuario WHERE grupo.idUsuarioCreador=usuario.idUsuario AND usuario.idUsuario='".$_SESSION['usuario_id']."'");
    
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

      for($i=0;$i<=5;$i++){
            if($i==3){ //columna nº alumnos
            $numalumnos = "";
            $idGrupo = $fila[0];
                $result2 = mysql_query("SELECT count(*) as total FROM usuario_grupo WHERE usuario_grupo.idGrupo='".$idGrupo."' AND usuario_grupo.solicitud=0");
                if($result2!=FALSE){
                    if($count=mysql_fetch_assoc($result2)){
                        $numalumnos=$count['total'];
                    }
                }               
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $cell->appendChild($dom->createCDATASection(utf8_encode($numalumnos)));
            }
            if($i==4){ //Columna de la imagen solicitud
                $result3 = mysql_query("SELECT usuario_grupo.solicitud FROM usuario_grupo WHERE usuario_grupo.idGrupo='".$fila[0]."' AND usuario_grupo.solicitud='1'");
                if($result3!=FALSE){
                     if($solicitud=mysql_fetch_assoc($result3)){
                            $cell= $row->appendChild($dom->createElement("cell"));
                            $domAtribute = $dom->createAttribute('type');
                            $domAtribute->value='img';
                            $cell->appendChild($domAtribute);
                            $contenido = ("../public/img/alert.png' id='req".$cont." ");
                            $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
                    }
                    else{
                        $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                        $cell->appendChild($dom->createCDATASection(utf8_encode("NO")));
                    }
                }
            }
            if($i==5){ //Columna de la imagen eliminar
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $contenido = ("../public/img/delete.png' id=".$cont."");
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