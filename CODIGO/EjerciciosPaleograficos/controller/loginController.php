 <?php
    session_start();
    include('../model/persistence/loginService.php');
	
    $method = $_REQUEST['method'];
    
    switch($method){
        case 'checkLogin':
            checkLogin();
            break;
        case 'login':
            login();
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
?> 