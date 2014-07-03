<?php
ob_start();
    //Configuraci�n Base de Datos
    if (!defined('BD')) define('BD', 'fuentesescritascyl');
    if (!defined('HOST')) define('HOST', 'localhost');
    if (!defined('USER')) define('USER', 'root');
    if (!defined('PASSWORD')) define('PASSWORD', 'root');

//conectamos y seleccionamos db 
$connection = mysql_connect(HOST,USER,PASSWORD) or die('Error: Imposible conectar a la base de datos del servidor.');
mysql_select_db(BD) or die('Error: Imposible seleccionar la base de datos.');
?>