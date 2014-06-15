 <?php
    //Configuración Base de Datos
	define("BD", "ejpaleo_prueba");
	define("HOST", "localhost1");
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
        echo 2;
        exit();
    }

?> 