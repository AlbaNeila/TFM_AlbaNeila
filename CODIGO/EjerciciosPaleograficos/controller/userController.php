<?php
	session_start();
	include('../model/persistence/userService.php');
    include('../model/persistence/groupService.php');
    
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
        case 'updatePassword':
            updatePassword();
            break;
    }
    
    function newStudent(){    	
        $usuario_nombre = mysql_real_escape_string($_POST['dnialumno']);
        $usuario_clave = mysql_real_escape_string($_POST['passwordalumno']);
        $nombre = mysql_real_escape_string($_POST['nombrealumno']);
    	$usuario_apellidos = mysql_real_escape_string($_POST['apellidosalumno']);
        $usuario_email = mysql_real_escape_string($_POST['emailalumno']);
        // comprobamos que el usuario ingresado no haya sido registrado antes
        $result = userService::getUserByName(utf8_decode($usuario_nombre));
        if($result==FALSE){ 
    				echo 0;
    	}
    	else{
    		if(!$row=mysqli_fetch_assoc($result)) {
    				$usuario_clave = md5($usuario_clave); // encriptamos la contraseña ingresada con md5
    	            // ingresamos los datos a la BD
    	            $reg = userService::insertUser(utf8_decode($usuario_nombre), $usuario_clave, utf8_decode($nombre), utf8_decode($usuario_apellidos), utf8_decode($usuario_email));
    	            if($reg) {
    					$result2 = userService::getUserByName(utf8_decode($usuario_nombre));
    					if($result2!=FALSE){ //Tenemos el idUsuario del nuevo usuario registrado
    						if($row=mysqli_fetch_assoc($result2)) {
    							$idUsuario = $row['idUsuario'];
    							$grupos   =   $_POST["grupos"];
    						    $grupos   =    json_decode("$grupos",true);
    						    foreach($grupos as $grupo){
									$reg2 = groupService::insertUsuarioGrupoSolicitud($idUsuario,$grupo);
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
        $usuario_clave = mysql_real_escape_string($_POST['password']);
        $nombre = mysql_real_escape_string($_POST['nombreprofesor']);
        $usuario_apellidos = mysql_real_escape_string($_POST['apellidosprofesor']);
        $usuario_email = mysql_real_escape_string($_POST['emailprofesor']);
        // comprobamos que el usuario ingresado no haya sido registrado antes
        $result = userService::getUserByName(utf8_decode($usuario_nombre));
        if($result==FALSE){ 
                    echo 0;
        }
        else{
            if(!$row=mysqli_fetch_assoc($result)) {
                    $usuario_clave = md5($usuario_clave); // encriptamos la contraseña ingresada con md5
                    // ingresamos los datos a la BD
                    $reg = userService::insertTeacher(utf8_decode($usuario_nombre), $usuario_clave, utf8_decode($nombre), utf8_decode($usuario_apellidos), utf8_decode($usuario_email));
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
            $result = groupService::getUsuarioGrupoByIds($group, $idStudent);
            if($permissions[$cont]==true){
                if(!$fila=mysqli_fetch_assoc($result)){//Si no hay filas -> Insert
                     $insert = groupService::insertUsuarioGrupo($idStudent, $group);
                     if(!$insert){
                         $flag = 0;
                     }
                }
            }
            else{
                if($fila=mysqli_fetch_assoc($result)){//Si hay filas -> Delete
                    $delete = groupService::deleteUsuarioGrupoByIds($group, $idStudent);
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
        
       $result = userService::deleteById($idUser);
        
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
        
        $result = userService::checkNameNotRepeat(utf8_decode($row[4]), $idUser);
        
        if($result!=FALSE){
            if(!$fila=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otro usuario con el mismo dni, por lo que actualizamos la fila
                $result2 = userService::updateById(utf8_decode($row[1]), utf8_decode($row[2]), utf8_decode($row[3]), utf8_decode($row[4]), utf8_decode($row[5]), $idUser);
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

    function updatePassword(){
        $newPass = mysqli_real_escape_string($GLOBALS['link'],$_POST['newPass']);
        $idUser = mysqli_real_escape_string($GLOBALS['link'],$_POST['idUser']);
        
        if($newPass!=""){
            $update = userService::updatePasswordById($newPass, $idUser);
            if($update){
                echo 1;
            }else{
                echo 0;
            }
        }else{
            echo 0;
        }
    }
?>