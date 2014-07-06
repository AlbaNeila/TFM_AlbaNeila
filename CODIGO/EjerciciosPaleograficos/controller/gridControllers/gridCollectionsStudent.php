<?php
    //PHP file to generate the XML document with the collections of a student, to load a dhtmlxgrid.
     
    header("Content-type: text/xml");   
    session_start();
    include('../../model/persistence/gridService.php');

    $result = gridService::getCollectionsByStudent($_SESSION['usuario_id']);
    
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
                $teacher = gridService::getTeacherOfCollection($idColeccion);
                if($teacher!=FALSE){
                    if($profesor=mysql_fetch_assoc($teacher)){
                        if($cont==1){
                            $contenido= "UBUPal";
                        }else{
                            $contenido=$profesor['nombre'];
                            $contenido.= " " .$profesor['apellidos'];
                        }
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