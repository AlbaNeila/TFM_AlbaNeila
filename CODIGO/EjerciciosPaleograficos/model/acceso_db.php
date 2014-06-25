<?php
 ob_start();
    //Configuración Base de Datos
    if (!defined('BD')) define('BD', 'fuentesescritascyl');
    if (!defined('HOST')) define('HOST', 'localhost');
    if (!defined('USER')) define('USER', 'root');
    if (!defined('PASSWORD')) define('PASSWORD', 'root');
    
    $flag=true;
    //conectamos y seleccionamos db 
    if(!$GLOBALS['link'] = mysqli_connect(HOST,USER,PASSWORD)){
        $flag=false;
    } 
    if(!mysqli_select_db($GLOBALS['link'] ,BD)){
        $flag = false;
    }
    @mysqli_query($GLOBALS['link'] ,"SET NAMES 'utf8'");
    if(!$flag){
        header('Location: ../view/error.php',FALSE);
        exit;
    }

?> 