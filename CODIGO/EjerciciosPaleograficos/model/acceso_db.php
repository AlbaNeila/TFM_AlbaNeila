 <?php
    //Configuración Base de Datos
	define("BD", "ejpaleo");
	define("HOST", "localhost");
	define("USER", "root");
	define("PASSWORD", "root");
    
    //conectamos y seleccionamos db	
	$GLOBALS['link'] = mysqli_connect(HOST,USER,PASSWORD) or die('Error: Imposible conectar a la base de datos del servidor.');
	mysqli_select_db($GLOBALS['link'] ,BD) or die('Error: Imposible seleccionar la base de datos.');

?> 