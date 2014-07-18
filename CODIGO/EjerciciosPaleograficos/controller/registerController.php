<?php
    //Check all registration fields and if all are correct create a new student with the data provided
    
	session_start();
	include('../model/persistence/userService.php');
    include('../model/persistence/groupService.php');
    
    
	$captcha="";
    $insertUser="";
    
    //Check the captcha value
	if(!empty($_POST['captcha'])){
	    if (empty($_SESSION['captcha']) || trim(strtolower($_POST['captcha'])) != $_SESSION['captcha']) {
	        $captcha = 'captcha';
	    }    
	}
    
    $usuario_nombre = mysqli_real_escape_string($GLOBALS['link'],$_POST['usuario_nombre']);
    $usuario_clave = mysqli_real_escape_string($GLOBALS['link'],$_POST['usuario_clave']);
    $nombre = mysqli_real_escape_string($GLOBALS['link'],$_POST['nombre']);
	$usuario_apellidos = mysqli_real_escape_string($GLOBALS['link'],$_POST['usuario_apellidos']);
    $usuario_email = mysqli_real_escape_string($GLOBALS['link'],$_POST['usuario_email']);
    //Check that the id don't exist in the data base
    $result = userService::getUserByName($usuario_nombre);
    if($result==FALSE){ 
		$insertUser = 'false';
	}
	else{
		if(!$row=mysqli_fetch_assoc($result)) {
			if($captcha!='captcha'){	
				$usuario_clave = md5($usuario_clave);
	            $reg = userService::insertUser($usuario_nombre, $usuario_clave, utf8_decode($nombre), utf8_decode($usuario_apellidos), $usuario_email);
	            if($reg) {
					$result2 = userService::getUserByName($usuario_nombre);
					if($result2!=FALSE){ 
						if($row=mysqli_fetch_assoc($result2)) {
							$idUsuario = $row['idUsuario'];
							$grupos   =   $_POST["grupos"];
						    $grupos   =    json_decode("$grupos",true);
						    foreach($grupos as $grupo){
						    	$idGroup = groupService::getByName(utf8_decode($grupo));
								if($idGroup!=FALSE){
										$reg2 = groupService::insertUserGroupRequest($idUsuario, $idGroup);
									}
								}
						    }
						}					    
					}
	            }	
           
		}
		else{
			$insertUser = 'repeatUsername';
		}
    }   
	
	$data = array(
		"captcha"=>$captcha,
		"insertUser"=>$insertUser,
	);
	$outputdata = json_encode($data);
	print($outputdata);  
	        
?>