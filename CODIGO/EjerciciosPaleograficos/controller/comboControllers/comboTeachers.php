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

    if($_REQUEST['method']=="admin"){
        $result = mysql_query("SELECT usuario.nombre,usuario.apellidos,usuario.idUsuario FROM usuario WHERE usuario.tipo='PROFESOR'");
    }
    
    
    header("Content-type: text/xml");
    $dom = new DOMDocument("1.0","UTF-8");
    $dom->formatOutput = true;
    $rows = $dom->appendChild($dom->createElement("complete"));
    $cont = 0;
    
    while ($fila = @mysql_fetch_array($result)){
        if($cont == 0){
            $domElement = $dom->createElement("option");
            $domAtribute = $dom->createAttribute('value');
            $domAtribute->value=utf8_encode($fila[2]);
            $domElement->appendChild($domAtribute);
            $row = $rows->appendChild($domElement);
            $domAtribute = $dom->createAttribute('selected');
            $domAtribute->value="selected";
            $domElement->appendChild($domAtribute);
            $row = $rows->appendChild($domElement);
            $nombre=utf8_encode($fila[0]);
            $apellidos=utf8_encode($fila[1]);
            $contenido="$nombre $apellidos ";
            $row->appendChild($dom->createCDATASection($contenido));
        }else{
            $domElement = $dom->createElement("option");
            $domAtribute = $dom->createAttribute('value');
            $domAtribute->value=utf8_encode($fila[2]);
            $domElement->appendChild($domAtribute);
            $row = $rows->appendChild($domElement);
            $nombre=utf8_encode($fila[0]);
            $apellidos=utf8_encode($fila[1]);
            $contenido="$nombre $apellidos ";
            $row->appendChild($dom->createCDATASection($contenido));
        }
        $cont++;     
    }
 
    echo $dom->saveXML();

?> 