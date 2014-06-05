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

    $result = mysql_query("SELECT distinct  documento.idDocumento,documento.nombre, documento.descripcion,documento.tipoEscritura, documento.fecha 
                            FROM usuario,usuario_grupo,grupo,grupo_coleccion,coleccion,coleccion_documento,documento
                            WHERE usuario.idUsuario='".$_SESSION['usuario_id']."' and usuario.idUsuario=usuario_grupo.idUsuario and usuario_grupo.idGrupo=grupo.idGrupo
                            and grupo.idGrupo=grupo_coleccion.idGrupo and grupo_coleccion.idColeccion=coleccion.idColeccion and coleccion.idColeccion=coleccion_documento.idColeccion and coleccion_documento.idDocumento=documento.idDocumento and coleccion.idColeccion='".$_REQUEST['idCollection']."'");
    
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

      for($i=0;$i<=6;$i++){
          if($i==6){ //Columna transcrito
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $result2 = mysql_query("SELECT ejercicio.idEjercicio FROM usuario,usuario_grupo,grupo,grupo_coleccion,coleccion,coleccion_documento,documento,ejercicio,grupo_ejercicio_coleccion
                                        WHERE usuario.idUsuario='".$_SESSION['usuario_id']."' and usuario.idUsuario=usuario_grupo.idUsuario and usuario_grupo.idGrupo=grupo.idGrupo and grupo.idGrupo IN (SELECT grupo.idGrupo FROM grupo, usuario, usuario_grupo WHERE usuario.idUsuario='".$_SESSION['usuario_id']."' and usuario.idUsuario=usuario_grupo.idUsuario and usuario_grupo.idGrupo=grupo.idGrupo) and grupo.idGrupo=grupo_coleccion.idGrupo and grupo_coleccion.idColeccion=coleccion.idColeccion and coleccion.idColeccion=coleccion_documento.idColeccion and coleccion_documento.idDocumento=documento.idDocumento and documento.idDocumento='".$fila[0]."' and documento.idDocumento=ejercicio.idDocumento and ejercicio.idEjercicio=grupo_ejercicio_coleccion.idEjercicio and grupo.idGrupo=grupo_ejercicio_coleccion.idGrupo");
                if(!$ejercicios = mysql_fetch_assoc($result2)){
                     $contenido = ("../public/img/minus.png' ");
                }
                else{
                    if($ejercicio = mysql_fetch_assoc($result2)){
                        $idEjercicio=$ejercicio['idEjercicio'];
                        $result3 = mysql_query("SELECT usuario_ejercicio.idEjercicio FROM usuario_ejercicio WHERE usuario_ejercicio.superado = '1' AND usuario_ejercicio.idEjercicio='".$idEjercicio."' AND usuario_ejercicio.idUsuario='".$_SESSION['usuario_id']."'");
                        if($superado = mysql_fetch_assoc($result3)){
                            $contenido = ("../public/img/yes.png' ");
                        }else{
                            $contenido = ("../public/img/no.png' ");
                        }  
                    }else{
                        $contenido = ("../public/img/no.png' ");
                    }               
                }  
                $cell->appendChild($dom->createCDATASection($contenido));
            }
            if($i==6){ //Columna ejercicio
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $result2 = mysql_query("SELECT ejercicio.idEjercicio FROM ejercicio WHERE ejercicio.idDocumento = '$fila[0]'");
                if(!$ejercicios = mysql_fetch_assoc($result2)){
                     $contenido = ("../public/img/minus.png' ");
                }
                else{
                    $result3 = mysql_query("SELECT usuario_ejercicio.idEjercicio FROM usuario_ejercicio WHERE usuario_ejercicio.superado = '0' AND usuario_ejercicio.idEjercicio=$fila[0] AND usuario_ejercicio.idUsuario='".$_SESSION['usuario_id']."'");
                    if($superado = mysql_fetch_assoc($result3)){
                        $contenido = ("../public/img/enter.png' ");
                    }else{
                        $contenido = ("../public/img/lock.png' ");
                    }                  
                }  
                $cell->appendChild($dom->createCDATASection($contenido));
            }
           
            if($i!=0 && $i!=5 && $i!=6){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $domAtribute = $dom->createAttribute('idDoc');
                $domAtribute->value=$fila[0];
                $cell->appendChild($domAtribute);
                $contenido = ("$fila[$i]");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
      }
    }
 
    echo $dom->saveXML();

?> 