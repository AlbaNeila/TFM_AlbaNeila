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


    $result = mysql_query("SELECT documento.idDocumento,documento.nombre, documento.descripcion,documento.tipoEscritura, documento.fecha FROM documento");
    
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

      for($i=0;$i<=8;$i++){
            if($i==5){ //Columna ejercicios
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $result2 = mysql_query("SELECT ejercicio.idEjercicio FROM ejercicio WHERE ejercicio.idDocumento = '$fila[0]'");
                if(!$ejercicios = mysql_fetch_assoc($result2)){
                     $contenido = ("../public/img/no.png' id='no");
                }
                else{
                    $contenido = ("../public/img/yes.png' id='si");
                }  
                $cell->appendChild($dom->createCDATASection($contenido));
            }
           if($i==6){ //Colecciones select
                $result3 = mysql_query("SELECT coleccion.nombre FROM coleccion,coleccion_documento WHERE coleccion_documento.idDocumento = '$fila[0]' AND coleccion_documento.idColeccion = coleccion.idColeccion");
                if(!$colecciones = mysql_fetch_assoc($result3)){
                     $contenido2 = ("../public/img/no.png' id='no");
                }
                else{
                    $contenido2 = ("../public/img/collection.png^^javascript:showCollections()^' id='si");
                }  
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $cell->appendChild($dom->createCDATASection(($contenido2)));
            }
           if($i==8){ //Columna de la imagen eliminar
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $contenido = ("../public/img/delete.png^^javascript:deleteDoc()^' id='".$cont."");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
           if($i==7){ //Columna de la imagen modificar ficheros
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='img';
                $cell->appendChild($domAtribute);
                $contenido = ("../public/img/editfiles.png^^javascript:editFiles()^' id='".$cont."");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i!=0 && $i!=6 && $i!=5 && $i!=7 && $i!=8){
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