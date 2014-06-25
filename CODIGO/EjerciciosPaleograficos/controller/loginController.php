<?php
ob_start();
    session_start();
    include_once("../model/persistence/loginService.php");
	
    $method = $_REQUEST['method'];
    
    switch($method){
        case 'checkLogin':
            checkLogin();
            break;
        case 'login':
            login();
            break;
        case 'forgotPassword':
            forgotPassword();
            break;

    }
    
    function checkLogin(){
    	$usuario_nombre = mysqli_real_escape_string($GLOBALS['link'],$_POST['usuario']);
        $usuario_clave = mysqli_real_escape_string($GLOBALS['link'],$_POST['password']);
        $usuario_clave=md5($usuario_clave);
    	$result = loginService::checkLogin($usuario_nombre, $usuario_clave);
    	
    	if($result==FALSE){
    		echo 0;
    	}
    	else{
    		if($row=mysqli_fetch_assoc($result)) {
    			$_SESSION['usuario_id'] = $row['idUsuario']; // creamos la sesion "usuario_id" y le asignamos como valor el campo usuario_id
    	        $_SESSION['usuario_nombre'] = $row["usuario"]; // creamos la sesion "usuario_nombre" y le asignamos como valor el campo usuario_nombre
    	        $_SESSION['usuario_tipo'] = $row["tipo"];
                echo 1;
    		}
    		else{
    			echo 0;
    		}
    	}
    }
    
    function login(){
        if($_SESSION['usuario_tipo'] == "PROFESOR"){
            header("Location: ../view/groupTeacher.php");
        }
        if($_SESSION['usuario_tipo'] == "ADMIN"){
            header("Location: ../view/usersAdmin.php");
        }
        if($_SESSION['usuario_tipo']== "ALUMNO"){
            header("Location: ../view/collectionsStudent.php");
        }
    }
    
    function newPassword(){
        $dni = mysqli_real_escape_string($GLOBALS['link'],$_POST['dni']);
        $result = userService::getUserByName($dni);
        if($result){
            $user = mysqli_fetch_assoc($result);
            $idUser = $user['idUsuario'];
            $email = $user['email'];
            $newPassword = generateRandomString();
            $result2=userService::updatePasswordById($newPassword, $idUser);
            if($result2){ //Send email
                $subject = 'echo(_("Nueva contraseña UBUPal:"))';
                $message = 'echo(_("Hola, tu nueva contraseña es: "))'+$newPassword;
                $headers = 'From: ubupal@ubu.es' . "\r\n";                
                mail($email, $subject, $message, $headers);
            }
        }
    }
    
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
?> 


















