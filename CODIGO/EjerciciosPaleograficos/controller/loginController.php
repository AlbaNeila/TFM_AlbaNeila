<?php
ob_start();
    session_start();
    include_once("../model/persistence/loginService.php");
    include_once("../model/persistence/userService.php");
	
    $method = $_REQUEST['method'];
    
    switch($method){
        case 'checkLogin':
            checkLogin();
            break;
        case 'login':
            login();
            break;
        case 'newPassword':
            newPassword();
            break;

    }
    
    /**
     * Check that the Id and password are correct and login to the application.
     *
     * Check if the id and the password received exist in the data base.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
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
    
    /**
     * Redirect to the main page of the application depends of the rol.
     *
     * Check the user rol that is accessing to the application and redirect to the main page of the application.
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function login(){
        if($_SESSION['usuario_tipo'] == "PROFESOR"){
            header("Location: ../view/groupTeacher.php");
        }
        if($_SESSION['usuario_tipo'] == "ADMIN"){
            header("Location: ../view/usersAdmin.php");
        }
        if($_SESSION['usuario_tipo']== "ALUMNO"){
            header("Location: ../view/groupStudent.php");
        }
    }
    
    /**
     * Update the password of the user.
     *
     * Create a new password to the user with the id received in the 'dni' post variable and send a notification.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function newPassword(){
        $dni = mysqli_real_escape_string($GLOBALS['link'],$_POST['dni']);
        $flag=0;
        $result = userService::getUserByName($dni);
        if($user = mysqli_fetch_assoc($result)){
            $idUser = $user['idUsuario'];
            $email = $user['email'];
            $newPassword = generateRandomString();
            $newPasswordEncript = md5($newPassword);
            $result2=userService::updatePasswordById($newPasswordEncript, $idUser);
            if($result2){ //Send email
                $subject = "New passsword UBUPal";
                $message = "Hello, you have received this message because the UBUPal user with ID:".$dni." has requested a new password.\n The new password is:".$newPassword."\n Regards.";
                $headers = 'From: ubupal@ubu.es' . "\r\n";                
                mail($email, $subject, $message, $headers);
                $flag=1;
            }
        }
        echo $flag;
    }
    
    /**
     * Generate a random string as password.
     *
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return $randomString new password
     */
    function generateRandomString() {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $randomString = '';
        for ($i = 0; $i < 5; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        for ($j = 0; $j < 4; $j++) {
            $randomString .= $numbers[rand(0, strlen($numbers) - 1)];
        }
        return $randomString;
    }
?> 


















