<?php      
	require_once("../lib/dhtmlxConnector_php/codebase/grid_connector.php");
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

	$result = mysql_query("SELECT grupo.nombre,usuario.nombre,usuario.idUsuario,grupo.idGrupo FROM grupo,usuario WHERE grupo.idUsuarioCreador=usuario.idUsuario AND usuario.tipo='PROFESOR'");
	
	header("Content-type: text/xml");
	$dom = new DOMDocument("1.0","iso-8859-1");
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
			$cell->appendChild($domAtribute);;
			$contenido = ("../public/img/info.png");
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