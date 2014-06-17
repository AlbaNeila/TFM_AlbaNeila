<?php    
    session_start();  

    include('../../model/grid_acceso_db.php');


    $result = mysql_query("SELECT coleccion.idColeccion,coleccion.nombre FROM coleccion");
    $result2 = mysql_query("SELECT coleccion_documento.idColeccion FROM coleccion_documento WHERE coleccion_documento.idDocumento='".$_REQUEST['idSearched']."'");

    
    $colecciones = array();
    while($row = mysql_fetch_array($result2))
        {
            $colecciones[] = $row['idColeccion'];
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

      for($i=0;$i<=2;$i++){
          $flag = false;
          if(in_array($fila[0],$colecciones)){
              $flag = true;
          }
            if($i==2){ //checkbox
                if($flag){
                    $contenido = '1';
                }
                else{
                    $contenido = '0';
                }
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
                
                $cell= $row->appendChild($dom->createElement("cell"));
                $domAtribute = $dom->createAttribute('type');
                $domAtribute->value='ch';
                $cell->appendChild($domAtribute);
                $domAtribute = $dom->createAttribute('idCol');
                $domAtribute->value=$fila[0];
                $cell->appendChild($domAtribute);

                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i!=0 && $i!=2){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $domAtribute = $dom->createAttribute('idCol');
                $domAtribute->value=$fila[0];
                $cell->appendChild($domAtribute);
                $contenido = ("$fila[$i]");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
      }
    }
 
    echo $dom->saveXML();

?> 