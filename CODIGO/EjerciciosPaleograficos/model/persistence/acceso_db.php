<?php
 ob_start();
    //Database configuration with local parameters
    
    if (!defined('BD')) define('BD', 'fuentesescritascyl');
    if (!defined('HOST')) define('HOST', 'localhost');
    if (!defined('USER')) define('USER', 'root');
    if (!defined('PASSWORD')) define('PASSWORD', 'root');

    $flag=true;
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