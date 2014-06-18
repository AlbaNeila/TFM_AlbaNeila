<?php    
    session_start();  
    include('../../model/grid_acceso_db.php');

    $result = mysql_query("SELECT distinct coleccion.idColeccion, coleccion.nombre,coleccion.descripcion FROM usuario_grupo,grupo,grupo_coleccion,coleccion,usuario WHERE usuario.idUsuario=usuario_grupo.idUsuario and usuario_grupo.idGrupo=grupo.idGrupo and grupo.idGrupo=grupo_coleccion.idGrupo and grupo_coleccion.idColeccion=coleccion.idColeccion and usuario.idUsuario='".$_SESSION['usuario_id']."' and grupo.idGrupo='".$_REQUEST['idGroup']."'");
    
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
          if($i==3){ //columna documentos
           $numdocumentos = "";
            $idColeccion = $fila[0];
                $result2 = mysql_query("SELECT count(*) as total FROM coleccion_documento WHERE coleccion_documento.idColeccion ='".$idColeccion."' ");
                if($result2!=FALSE){
                    if($count=mysql_fetch_assoc($result2)){
                        $numdocumentos=$count['total'];
                    }
                }               
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $cell->appendChild($dom->createCDATASection(utf8_encode($numdocumentos)));
            }
            if($i==4){ //Columna ejercicios
               $numdocumentos = "";
                $idColeccion = $fila[0];
                $result2 = mysql_query("SELECT distinct count(ejercicio.idEjercicio) as total FROM usuario,usuario_grupo,grupo,grupo_ejercicio_coleccion,ejercicio WHERE usuario.idUsuario='".$_SESSION['usuario_id']."' and usuario.idUsuario=usuario_grupo.idUsuario and usuario_grupo.idGrupo=grupo.idGrupo and grupo.idGrupo=grupo_ejercicio_coleccion.idGrupo and grupo_ejercicio_coleccion.idColeccion='".$idColeccion."' and ejercicio.idEjercicio=grupo_ejercicio_coleccion.idEjercicio ");
                if($result2!=FALSE){
                    if($count=mysql_fetch_assoc($result2)){
                        $numdocumentos=$count['total'];
                    }
                }               
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $cell->appendChild($dom->createCDATASection(utf8_encode($numdocumentos)));
            }
            if($i==5){ //Columna de la imagen entrar
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $contenido = ("../public/img/enter.png^^javascript:accessCollection()^'");
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