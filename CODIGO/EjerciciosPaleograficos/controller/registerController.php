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
    $usuario_nombre = mysql_real_escape_string($_POST['usuario_nombre']);
    $usuario_clave = mysql_real_escape_string($_POST['usuario_clave']);
    $nombre = mysql_real_escape_string($_POST['nombre']);
	$usuario_apellidos = mysql_real_escape_string($_POST['usuario_apellidos']);
    $usuario_email = mysql_real_escape_string($_POST['usuario_email']);
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
	            $reg = userService::insertUser($usuario_nombre, $usuario_clave, $nombre, $usuario_apellidos, $usuario_email);
	            if($reg) {
					$result2 = userService::getUserByName($usuario_nombre);
					if($result2!=FALSE){ //Tenemos el idUsuario del nuevo usuario registrado
						if($row=mysqli_fetch_assoc($result2)) {
							$idUsuario = $row['idUsuario'];
							$grupos   =   $_POST["grupos"];
						    $grupos   =    json_decode("$grupos",true);
						    foreach($grupos as $grupo){
						    	$result3 = groupService::getByName(utf8_decode($grupo));
								if($result3!=FALSE){ //Tenemos el idGrupo del grupo al que se ha solicitado acceso
									if($row=mysqli_fetch_assoc($result3)) {
										$idGrupo = $row['idGrupo'];
										$reg2 = groupService::insertUsuarioGrupoSolicitud($idUsuario, $idGrupo);
									}
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