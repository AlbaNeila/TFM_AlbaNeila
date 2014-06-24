<?php
ob_start();
    //Configuración Base de Datos
    if (!defined('BD')) define('BD', 'fuentesescritascyl');
    if (!defined('HOST')) define('HOST', 'mac.ubu.es');
    if (!defined('USER')) define('USER', 'fuentesescritasc');
    if (!defined('PASSWORD')) define('PASSWORD', 'Laesae2X');

//conectamos y seleccionamos db 
$connection = mysql_connect(HOST,USER,PASSWORD) or die('Error: Imposible conectar a la base de datos del servidor.');
mysql_select_db(BD) or die('Error: Imposible seleccionar la base de datos.');
?>