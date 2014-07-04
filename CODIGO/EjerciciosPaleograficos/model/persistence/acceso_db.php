<?php
 ob_start();
    //Configuraci�n Base de Datos
    if (!defined('BD')) define('BD', 'fuentesescritascyl');
    if (!defined('HOST')) define('HOST', 'mac.ubu.es');
    if (!defined('USER')) define('USER', 'fuentesescritasc');
    if (!defined('PASSWORD')) define('PASSWORD', 'Laesae2X');

    $flag=true;
    //conectamos y seleccionamos db 
    if(!$GLOBALS['link'] = mysqli_connect(HOST,USER,PASSWORD)){
        $flag=false;
    } 
    if(!mysqli_select_db($GLOBALS['link'] ,BD)){
        $flag = false;
    }
    
    if(!$flag){
        header('Location: ../view/error.php',FALSE);
        exit;
    }

?> 