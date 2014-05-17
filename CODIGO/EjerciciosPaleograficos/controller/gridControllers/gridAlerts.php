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
    
    $idGrupo = $_REQUEST['idGrupo'];

    $result = mysql_query("SELECT usuario.idUsuario,usuario.nombre,usuario.apellidos,usuario.email FROM usuario,usuario_grupo WHERE usuario.idUsuario = usuario_grupo.idUsuario AND usuario_grupo.solicitud = 1 AND usuario_grupo.idGrupo = '".$idGrupo."'");
    
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
         if($i==0){
            $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
            $domAtribute = $dom->createAttribute('id');
            $domAtribute->value=$fila[0];
            $cell->appendChild($domAtribute);
            $contenido = ("$fila[1]");
            $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
         }
        if($i==4){ //Columna del checkbox
            $cell= $row->appendChild($dom->createElement("cell"));
            $domAtribute = $dom->createAttribute('type');
            $domAtribute->value='ch';
            $cell->appendChild($domAtribute);
            $contenido = ("0");
            $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
        }
        if($i!=4 && $i!=5 && $i!=0 && $i!=1){
            $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
            $contenido = ("$fila[$i]");
            $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
        }
      }
    }
 
    echo $dom->saveXML();

?> 