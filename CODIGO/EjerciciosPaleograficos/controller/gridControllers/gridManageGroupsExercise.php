<?php    
    session_start();  

    include('../../model/grid_acceso_db.php');

    

   $result = mysql_query("SELECT grupo.idGrupo,grupo.nombre FROM grupo,grupo_coleccion WHERE grupo.idGrupo=grupo_coleccion.idGrupo AND grupo_coleccion.idColeccion='".$_REQUEST['idCollection']."'");
   $result2 = mysql_query("SELECT grupo_ejercicio_coleccion.idGrupo FROM grupo_ejercicio_coleccion WHERE grupo_ejercicio_coleccion.idEjercicio='".$_REQUEST['idSearched']."'");

    
    $groups = array();
    while($row = mysql_fetch_array($result2))
        {
            $groups[] = $row['idGrupo'];
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
          if(in_array($fila[0],$groups)){
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
                $domAtribute = $dom->createAttribute('idEj');
                $domAtribute->value=$fila[0];
                $cell->appendChild($domAtribute);

                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
            if($i!=0 && $i!=2){
                $cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
                $domAtribute = $dom->createAttribute('idEj');
                $domAtribute->value=$fila[0];
                $cell->appendChild($domAtribute);
                $contenido = ("$fila[$i]");
                $cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
            }
      }
    }
 
    echo $dom->saveXML();

?> 