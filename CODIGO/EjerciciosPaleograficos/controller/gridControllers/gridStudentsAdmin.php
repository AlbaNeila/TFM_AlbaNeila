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

    
    $result = mysql_query("SELECT usuario.idUsuario,usuario.nombre,usuario.apellidos,usuario.email,usuario.usuario,usuario.password FROM usuario WHERE usuario.tipo='ALUMNO'");
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
        
        $numgrupos = "";
      for($i=0;$i<=8;$i++){           
            if($i==8){ //Columna de la imagen eliminar
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $contenido = (" ../public/img/delete.png^^javascript:deleteStudent()^' id='".$fila[0]."");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i==7){ //Columna de la imagen editar
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                if($numgrupos!=0){
                    $contenido = (" ../public/img/groups.png^^javascript:consultGroups()^' id='".$fila[0]."");
                }else{
                    $contenido = (" ../public/img/nogroups.png^^javascript:consultGroups()^'id='".$fila[0]."");
                }
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i==6){ //columna nº de grupos
            $idUsuario = $fila[0];
                $result2 = mysql_query("SELECT count(*) as total FROM usuario_grupo WHERE usuario_grupo.idUsuario ='".$idUsuario."' AND usuario_grupo.solicitud='0' ");
                if($result2!=FALSE){
                    if($count=mysql_fetch_assoc($result2)){
                        $numgrupos=$count['total'];
                    }
                }               
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>              
                $cell->appendChild($dom->createCDATASection(utf8_encode($numgrupos)));
            }
            if($i==5){ //columna cambiar contraseña
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $domAtribute = $dom->createAttribute('idStudent');
                $domAtribute->value=$fila[0];
                $cell->appendChild($domAtribute);
                $contenido = (" ../public/img/changepassword.png^^javascript:changePassword()^' id='".$fila[0]."");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i!=5 && $i!=6 && $i!=7 && $i!=8){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $contenido = ("$fila[$i]");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
      }
    }
 
    echo $dom->saveXML();

?> 