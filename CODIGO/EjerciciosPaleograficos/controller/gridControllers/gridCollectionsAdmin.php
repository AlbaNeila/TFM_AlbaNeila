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


    $result = mysql_query("SELECT coleccion.idColeccion, coleccion.nombre,coleccion.descripcion,coleccion.ordenada FROM coleccion");
    
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
      for($i=0;$i<=7;$i++){
          if($i==3){ //columna nº de documentos
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
            if($i==4){ //columna nº de grupos
            $numgrupos = "";
            $idColeccion = $fila[0];
                $result3 = mysql_query("SELECT count(*) as total FROM grupo_coleccion WHERE grupo_coleccion.idColeccion='".$idColeccion."' ");
                if($result3!=FALSE){
                    if($count=mysql_fetch_assoc($result3)){
                        $numgrupos=$count['total'];
                    }
                }               
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $cell->appendChild($dom->createCDATASection(utf8_encode($numgrupos)));
            }
            if($i==5){ //Columna de la imagen editar grupos
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
            if($i==7){ //Columna de la imagen eliminar
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $contenido = ("../public/img/delete.png^^javascript:deleteCollection()^' id='".$cont."");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i!=3 && $i!=4 && $i!=6 && $i!=5 && $i!=6 && $i!=7){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $contenido = ("$fila[$i]");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
      }
    }
 
    echo $dom->saveXML();

?> 