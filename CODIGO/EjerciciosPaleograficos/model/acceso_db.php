 <?php
    //Configuración Base de Datos
    define("BD", "ubupal");
    define("HOST", "localhost");
    define("USER", "root");
    define("PASSWORD", "root");
    
    $flag=true;
    //conectamos y seleccionamos db	
	if(!$GLOBALS['link'] = @mysqli_connect(HOST,USER,PASSWORD)){
        $flag=false;
	} 
	if(!@mysqli_select_db($GLOBALS['link'] ,BD)){
        $flag = false;
	}
    if(!$flag){
        header('Location: ../view/error.php',FALSE);
        exit;
    }

?> 