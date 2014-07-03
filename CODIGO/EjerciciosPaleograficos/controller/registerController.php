<?php
	session_start();
	include('../model/persistence/userService.php');
    include('../model/persistence/groupService.php');
    
    // Procedemos a comprobar que los campos del formulario no estén vacíos
	$captcha="";
    $insertUser="";
    
	if(!empty($_POST['captcha'])){ //comprobamos que el campo captcha haya sido ingresado correctamente
	    if (empty($_SESSION['captcha']) || trim(strtolower($_POST['captcha'])) != $_SESSION['captcha']) {
	        $captcha = 'captcha';
	    }    
	}
	
    // "limpiamos" los campos del formulario de posibles códigos maliciosos
    $usuario_nombre = mysqli_real_escape_string($GLOBALS['link'],$_POST['usuario_nombre']);
    $usuario_clave = mysqli_real_escape_string($GLOBALS['link'],$_POST['usuario_clave']);
    $nombre = mysqli_real_escape_string($GLOBALS['link'],$_POST['nombre']);
	$usuario_apellidos = mysqli_real_escape_string($GLOBALS['link'],$_POST['usuario_apellidos']);
    $usuario_email = mysqli_real_escape_string($GLOBALS['link'],$_POST['usuario_email']);
    // comprobamos que el usuario ingresado no haya sido registrado antes
    $result = userService::getUserByName($usuario_nombre);
    if($result==FALSE){ 
		$insertUser = 'false';
	}
	else{
		if(!$row=mysqli_fetch_assoc($result)) {
			if($captcha!='captcha'){	//si el captcha y el usuario están OK insertamos en la bd
				$usuario_clave = md5($usuario_clave); // encriptamos la contraseña ingresada con md5
	            // ingresamos los datos a la BD
	            $reg = userService::insertUser($usuario_nombre, $usuario_clave, utf8_decode($nombre), utf8_decode($usuario_apellidos), $usuario_email);
	            if($reg) {
					$result2 = userService::getUserByName($usuario_nombre);
					if($result2!=FALSE){ //Tenemos el idUsuario del nuevo usuario registrado
						if($row=mysqli_fetch_assoc($result2)) {
							$idUsuario = $row['idUsuario'];
							$grupos   =   $_POST["grupos"];
						    $grupos   =    json_decode("$grupos",true);
						    foreach($grupos as $grupo){
						    	$idGroup = groupService::getByName(utf8_decode($grupo));
								if($idGroup!=FALSE){ //Tenemos el idGrupo del grupo al que se ha solicitado acceso
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