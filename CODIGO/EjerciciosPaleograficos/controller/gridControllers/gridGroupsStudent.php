<?php    
    session_start();  

    include('../../model/grid_acceso_db.php');


    $result = mysql_query("SELECT grupo.idGrupo,grupo.nombre,grupo.descripcion FROM grupo,usuario,usuario_grupo WHERE usuario.idUsuario=usuario_grupo.idUsuario AND usuario_grupo.idGrupo=grupo.idGrupo AND usuario_grupo.solicitud=0 and usuario.idUsuario='".$_SESSION['usuario_id']."'");
    
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

      for($i=0;$i<=4;$i++){
            if($i==3){ //columna profesor responsable
            $contenido = "";
            $idGroup = $fila[0];
                $teacher = mysql_query("SELECT distinct usuario.nombre,usuario.apellidos FROM usuario,grupo WHERE usuario.idUsuario=grupo.idUsuarioCreador AND grupo.idGrupo='".$idGroup."'");
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
                $contenido = ("../public/img/enter.png^^javascript:accessGroup()^' id='".$cont."");
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