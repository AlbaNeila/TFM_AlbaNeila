<?php    
    session_start();  

    include('../../model/grid_acceso_db.php');

    $idSearched = $_REQUEST['idSearched'];
    if($_REQUEST['method'] == 'student'){
        $result = mysql_query("SELECT grupo.idGrupo,grupo.nombre FROM grupo");
        $result2 = mysql_query("SELECT usuario_grupo.idGrupo FROM usuario_grupo WHERE usuario_grupo.idUsuario='".$idSearched."' AND usuario_grupo.solicitud=0");
    }
    if($_REQUEST['method'] == 'collectionAdmin'){
        $result = mysql_query("SELECT grupo.idGrupo,grupo.nombre FROM grupo");
        $result2 = mysql_query("SELECT grupo_coleccion.idGrupo FROM grupo_coleccion WHERE grupo_coleccion.idColeccion='".$idSearched."'");
    }
   if($_REQUEST['method'] == 'collection'){
        $result = mysql_query("SELECT grupo.idGrupo,grupo.nombre FROM grupo,usuario WHERE grupo.idUsuarioCreador=usuario.idUsuario AND usuario.idUsuario='".$_SESSION['usuario_id']."'");
        $result2 = mysql_query("SELECT grupo_coleccion.idGrupo FROM grupo_coleccion WHERE grupo_coleccion.idColeccion='".$idSearched."'");
    }

    
    $grupos = array();
    while($row = mysql_fetch_array($result2))
        {
            $grupos[] = $row['idGrupo'];
        }
    
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

      for($i=0;$i<=3;$i++){
          $flag = false;
          if(in_array($fila[0],$grupos)){
              $flag = true;
          }
            if($i==2){ //Radiobutton aceptar
                $cell= $row->appendChild($dom->createElement("cell"));
                if($flag){
                    $contenido = ("<input type='radio' name='radio$cont' checked='checked'></input>");
                }
                else{
                    $contenido = ("<input type='radio' name='radio$cont'></input>");
                }
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i==3){ //Radiobutton rechazar
                $cell= $row->appendChild($dom->createElement("cell"));
                if(!$flag){
                    $contenido = ("<input type='radio' name='radio$cont' checked='checked'></input>");
                }
                else{
                    $contenido = ("<input type='radio' name='radio$cont'></input>");
                }
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i!=0 && $i!=2 && $i!=3){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $domAtribute = $dom->createAttribute('idGroup');
                $domAtribute->value=$fila[0];
                $cell->appendChild($domAtribute);
                $contenido = ("$fila[$i]");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
      }
    }
 
    echo $dom->saveXML();

?> 