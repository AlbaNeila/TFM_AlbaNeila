<?php
	session_start();
	include('../model/acceso_db.php');
    $method = $_POST['method'];
    
    switch($method){
        case 'newStudent':
            newStudent();
            break;
        case 'newTeacher':
            newTeacher();
            break;
        case 'updatePermissionsStudent':
            updatePermissionsStudent();
            break;
        case 'deleteUser':
            deleteUser();
            break;
        case 'checkUpdateGridUser':
            checkUpdateGridUser();
            break;
    }
    
    function newStudent(){    	
        $usuario_nombre = mysql_real_escape_string($_POST['dnialumno']);
        $usuario_clave = mysql_real_escape_string($_POST['passwordalumno']);
        $nombre = mysql_real_escape_string($_POST['nombrealumno']);
    	$usuario_apellidos = mysql_real_escape_string($_POST['apellidosalumno']);
        $usuario_email = mysql_real_escape_string($_POST['emailalumno']);
        // comprobamos que el usuario ingresado no haya sido registrado antes
        $result = mysqli_query($GLOBALS['link'],"SELECT usuario.usuario FROM usuario WHERE usuario.usuario='".$usuario_nombre."'");
        if($result==FALSE){ 
    				echo 0;
    	}
    	else{
    		if(!$row=mysqli_fetch_assoc($result)) {
    				$usuario_clave = md5($usuario_clave); // encriptamos la contraseña ingresada con md5
    	            // ingresamos los datos a la BD
    	            $reg = mysqli_query($GLOBALS['link'],"INSERT INTO usuario (usuario.usuario, usuario.password, usuario.nombre, usuario.apellidos, usuario.email, usuario.tipo) VALUES ('".utf8_decode($usuario_nombre)."', '".utf8_decode($usuario_clave)."', '".utf8_decode($nombre)."','".utf8_decode($usuario_apellidos)."','".utf8_decode($usuario_email)."', 'ALUMNO')");
    	            if($reg) {
    					$result2 = mysqli_query($GLOBALS['link'],"SELECT usuario.idUsuario FROM usuario WHERE usuario.usuario='".$usuario_nombre."'");
    					if($result2!=FALSE){ //Tenemos el idUsuario del nuevo usuario registrado
    						if($row=mysqli_fetch_assoc($result2)) {
    							$idUsuario = $row['idUsuario'];
    							$grupos   =   $_POST["grupos"];
    						    $grupos   =    json_decode("$grupos",true);
    						    foreach($grupos as $grupo){
									$reg2 = mysqli_query($GLOBALS['link'],"INSERT INTO usuario_grupo (usuario_grupo.idUsuario,usuario_grupo.idGrupo,usuario_grupo.solicitud) VALUES ('".$idUsuario."', '".$grupo."', '1')");
                                    if($reg2)
                                    {
                                        echo 1;
                                    }
    						    }
    						}					    
    					}
    	            }	
    		}
    		else{
    			echo 2;
    		}
        }    
	} 

    function newTeacher(){      
        $usuario_nombre = mysql_real_escape_string($_POST['dniprofesor']);
        $usuario_clave = mysql_real_escape_string($_POST['passwordprofesor']);
        $nombre = mysql_real_escape_string($_POST['nombreprofesor']);
        $usuario_apellidos = mysql_real_escape_string($_POST['apellidosprofesor']);
        $usuario_email = mysql_real_escape_string($_POST['emailprofesor']);
        // comprobamos que el usuario ingresado no haya sido registrado antes
        $result = mysqli_query($GLOBALS['link'],"SELECT usuario.usuario FROM usuario WHERE usuario.usuario='".$usuario_nombre."'");
        if($result==FALSE){ 
                    echo 0;
        }
        else{
            if(!$row=mysqli_fetch_assoc($result)) {
                    $usuario_clave = md5($usuario_clave); // encriptamos la contraseña ingresada con md5
                    // ingresamos los datos a la BD
                    $reg = mysqli_query($GLOBALS['link'],"INSERT INTO usuario (usuario.usuario, usuario.password, usuario.nombre, usuario.apellidos, usuario.email, usuario.tipo) VALUES ('".utf8_decode($usuario_nombre)."', '".utf8_decode($usuario_clave)."', '".utf8_decode($nombre)."','".utf8_decode($usuario_apellidos)."','".utf8_decode($usuario_email)."', 'PROFESOR')");
                    if($reg) {
                        echo 1;
                    }   
            }
            else{
                echo 2;
            }
        }    
    } 

    function updatePermissionsStudent(){
        $groups = $_POST["groups"];
        $groups= json_decode("$groups",true);
        $permissions = $_POST["permissions"];
        $permissions= json_decode("$permissions",true);
        $idStudent = $_POST['idStudent'];
        
        $cont=0;
        $flag=1;
        
        foreach($groups as $group){
            $result = mysqli_query($GLOBALS['link'],"SELECT usuario_grupo.idGrupo FROM usuario_grupo WHERE usuario_grupo.idGrupo= '".$group."' and usuario_grupo.idUsuario='".$idStudent."'");
            if($permissions[$cont]==true){
                if(!$fila=mysqli_fetch_assoc($result)){//Si no hay filas -> Insert
                     $insert = mysqli_query($GLOBALS['link'],"INSERT INTO usuario_grupo (usuario_grupo.idGrupo, usuario_grupo.idUsuario) VALUES ('".$group."','".$idStudent."')");
                     if(!$insert){
                         $flag = 0;
                     }
                }
            }
            else{
                if($fila=mysqli_fetch_assoc($result)){//Si hay filas -> Delete
                    $delete = mysqli_query($GLOBALS['link'],"DELETE FROM usuario_grupo WHERE usuario_grupo.idGrupo= '".$group."' AND usuario_grupo.idUsuario='".$idStudent."'");
                    if(!$delete){
                         $flag = 0;
                     }
                }
            }
            $cont++;
        }
        echo $flag;
    }     
    
    function deleteUser(){
        $idUser = mysqli_real_escape_string($GLOBALS['link'],$_POST['idUser']);
        
       $result = mysqli_query($GLOBALS['link'],"DELETE FROM usuario WHERE usuario.idUsuario= '".$idUser."'");
        
        if($result!=FALSE){
                    echo 1; //Delete usuario OK
        }
        else{
            echo 0; //Error
        }
    }  
    
    function checkUpdateGridUser(){
        $row = $_POST["row"];
        $row = json_decode("$row",true);
        $idUser = $_POST['idUser'];
        
        $result = mysqli_query($GLOBALS['link'],"SELECT usuario.nombre FROM usuario WHERE usuario.usuario= '".$row[4]."' and usuario.idUsuario<>'".$idUser."'");
        
        if($result!=FALSE){
            if(!$fila=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otro usuario con el mismo dni, por lo que actualizamos la fila
                $result2 = mysqli_query($GLOBALS['link'],"UPDATE usuario SET usuario.nombre='".utf8_decode($row[1])."', usuario.apellidos='".utf8_decode($row[2])."',usuario.email='".utf8_decode($row[3])."' ,usuario.usuario='".utf8_decode($row[4])."',usuario.password='".md5($row[5])."' WHERE usuario.idUsuario='".$idUser."'");
                if($result2!=FALSE)
                    echo 1;
            }
            else{
                echo 0;
            }
        }
        else{
            echo 0;
        }
    }
?>