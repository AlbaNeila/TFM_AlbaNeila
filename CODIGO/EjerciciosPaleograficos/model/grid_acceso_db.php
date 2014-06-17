<?php
define("BD", "ubupal");
define("HOST", "localhost");
define("USER", "root");
define("PASSWORD", "root");

//conectamos y seleccionamos db 
$connection = mysql_connect(HOST,USER,PASSWORD) or die('Error: Imposible conectar a la base de datos del servidor.');
mysql_select_db(BD) or die('Error: Imposible seleccionar la base de datos.');
?>