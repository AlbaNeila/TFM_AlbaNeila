<?php    
    session_start();  
    include('../../model/grid_acceso_db.php');

    if($_REQUEST['method']=="admin"){
        $result = mysql_query("SELECT grupo.nombre,grupo.idGrupo FROM grupo");
    }
    if($_REQUEST['method']=="adminExercises"){
        $result = mysql_query("SELECT grupo.nombre,grupo.idGrupo FROM grupo,grupo_coleccion WHERE grupo.idGrupo=grupo_coleccion.idGrupo AND grupo_coleccion.idColeccion='".$_REQUEST['idCollection']."'");
    }
    if($_REQUEST['method']=="otro"){
        $result = mysql_query("SELECT grupo.nombre,grupo.idGrupo FROM grupo WHERE grupo.idUsuarioCreador = '".$_SESSION['usuario_id']."'");
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
            $domAtribute->value=utf8_encode($fila[1]);
            $domElement->appendChild($domAtribute);
            $row = $rows->appendChild($domElement);
            $domAtribute = $dom->createAttribute('selected');
            $domAtribute->value="selected";
            $domElement->appendChild($domAtribute);
            $row = $rows->appendChild($domElement);
            $row->appendChild($dom->createCDATASection(utf8_encode($fila[0])));
        }else{
            $domElement = $dom->createElement("option");
            $domAtribute = $dom->createAttribute('value');
            $domAtribute->value=utf8_encode($fila[1]);
            $domElement->appendChild($domAtribute);
            $row = $rows->appendChild($domElement);
            $row->appendChild($dom->createCDATASection(utf8_encode($fila[0])));
        }
        $cont++;     
    }
 
    echo $dom->saveXML();

?> 