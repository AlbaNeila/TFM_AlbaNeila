<?php      

	include('../../model/persistence/gridService.php');


	$result = gridService::getGroupsToRegister();
	
	header("Content-type: text/xml");
	$dom = new DOMDocument("1.0","UTF-8");
	$dom->formatOutput = true;
	$rows = $dom->appendChild($dom->createElement("rows"));
	$cont = 0;
	
	while ($fila = @mysql_fetch_array($result)){
		$domElement = $dom->createElement("row");
		$domAtribute = $dom->createAttribute('id');
		$domAtribute->value=$cont++;
		$domElement->appendChild($domAtribute);
	  	$row = $rows->appendChild($domElement); //añadimos <row>

	  for($i=0;$i<mysql_num_fields($result);$i++){
	  	if($i==2){ //Columna de la imagen
	  		$cell= $row->appendChild($dom->createElement("cell"));
			$domAtribute = $dom->createAttribute('type');
			$domAtribute->value='img';
			$cell->appendChild($domAtribute);
            $domAtribute = $dom->createAttribute('idGroup');
            $domAtribute->value=$fila[3];
            $cell->appendChild($domAtribute);
			$contenido = ("../public/img/info.png^^javascript:consultInfoGroup()^' id='".$cont."");
			$cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
	  	}
	  	if($i==3){ //Columna del checkbox
	  		$cell= $row->appendChild($dom->createElement("cell"));
			$domAtribute = $dom->createAttribute('type');
			$domAtribute->value='ch';
			$cell->appendChild($domAtribute);
			$contenido = ("0");
			$cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
	  	}
	  	if($i!=2 && $i!=3){ //Resto de columnas
		  	$cell= $row->appendChild($dom->createElement("cell")); //añadimos <cell>
		  	$contenido = ("$fila[$i]");
		  	$cell->appendChild($dom->createCDATASection(utf8_encode($contenido)));
	    }
	  }
	 
	}
 
	echo $dom->saveXML();

?> 