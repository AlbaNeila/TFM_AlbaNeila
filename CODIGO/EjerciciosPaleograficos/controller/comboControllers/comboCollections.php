<?php      
      session_start();  
    //Configuración Base de Datos
    define("BD", "EJPALEO");
    define("HOST", "localhost");
    define("USER", "root");
    define("PASSWORD", "root");
    
    //conectamos y seleccionamos db 
    $connection = mysql_connect(HOST,USER,PASSWORD) or die('Error: Imposible conectar a la base de datos del servidor.');
    mysql_select_db(BD) or die('Error: Imposible seleccionar la base de datos.');


    $result = mysql_query("SELECT DISTINCT coleccion.nombre,coleccion.idColeccion FROM grupo,grupo_coleccion,coleccion WHERE grupo.idUsuarioCreador = '".$_SESSION['usuario_id']."' AND grupo.idGrupo=grupo_coleccion.idGrupo AND grupo_coleccion.idColeccion = coleccion.idColeccion");
    
    header("Content-type: text/xml");
    $dom = new DOMDocument("1.0","UTF-8");
    $dom->formatOutput = true;
    $rows = $dom->appendChild($dom->createElement("complete"));
    $cont = 0;
    
    while ($fila = @mysql_fetch_array($result)){
        $domElement = $dom->createElement("option");
        $domAtribute = $dom->createAttribute('value');
        $domAtribute->value=utf8_encode($fila[1]);
        $domElement->appendChild($domAtribute);
        $row = $rows->appendChild($domElement);
        $row->appendChild($dom->createCDATASection(utf8_encode($fila[0])));
        $cont++;     
    }
 
    echo $dom->saveXML();

?> 