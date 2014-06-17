<?php    
    session_start();  

    include('../../model/grid_acceso_db.php');

    $result = mysql_query("SELECT distinct coleccion.idColeccion, coleccion.nombre,coleccion.descripcion FROM usuario_grupo,grupo,grupo_coleccion,coleccion,usuario WHERE usuario.idUsuario=usuario_grupo.idUsuario and usuario_grupo.idGrupo=grupo.idGrupo and grupo.idGrupo=grupo_coleccion.idGrupo and grupo_coleccion.idColeccion=coleccion.idColeccion and usuario.idUsuario='".$_SESSION['usuario_id']."'");
    
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
            $idColeccion = $fila[0];
                $teacher = mysql_query("SELECT distinct usuario.nombre,usuario.apellidos FROM usuario,grupo,grupo_coleccion,coleccion WHERE usuario.idUsuario=grupo.idUsuarioCreador AND grupo.idGrupo=grupo_coleccion.idGrupo AND grupo_coleccion.idColeccion=coleccion.idColeccion AND coleccion.idColeccion='".$idColeccion."'");
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
                $contenido = ("../public/img/enter.png^^javascript:accessCollection()^' id='".$cont."");
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