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
    
    /**
     * Function to add a new Student.
     *
     * Create a new student user by the adminsitrator.
     * Echo 0 if error
     * Echo 1 if ok
     * Echo 2 if if also exist an student with the same id
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version  1.0
     *  @access   public
     *  @return void
     */
    function newStudent(){    	
        $usuario_nombre = mysqli_real_escape_string($GLOBALS['link'],$_POST['dnialumno']);
        $usuario_clave = mysqli_real_escape_string($GLOBALS['link'],$_POST['passwordalumno']);
        $nombre = mysqli_real_escape_string($GLOBALS['link'],$_POST['nombrealumno']);
    	$usuario_apellidos = mysqli_real_escape_string($GLOBALS['link'],$_POST['apellidosalumno']);
        $usuario_email = mysqli_real_escape_string($GLOBALS['link'],$_POST['emailalumno']);
        $result = userService::getUserByName(utf8_decode($usuario_nombre));
        if($result==FALSE){ 
    				echo 0;
    	}
    	else{
    		if(!$row=mysqli_fetch_assoc($result)) {
    				$usuario_clave = md5($usuario_clave);
    	            $reg = userService::insertUser(utf8_decode($usuario_nombre), $usuario_clave, utf8_decode($nombre), utf8_decode($usuario_apellidos), utf8_decode($usuario_email));
    	            if($reg) {
    					$result2 = userService::getUserByName(utf8_decode($usuario_nombre));
    					if($result2!=FALSE){ 
    						if($row=mysqli_fetch_assoc($result2)) {
    							$idUsuario = $row['idUsuario'];
    							$grupos   =   $_POST["grupos"];
    						    $grupos   =    json_decode("$grupos",true);
    						    foreach($grupos as $grupo){
									$reg2 = groupService::insertUserGroupRequest($idUsuario,$grupo);
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

    /**
     * Function to add a new Teacher.
     *
     * Create a new teacher user by the adminsitrator.
     * Echo 0 if error
     * Echo 1 if ok
     * Echo 2 if if also exist a teacher with the same id
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version  1.0
     *  @access   public
     *  @return void
     */
    function newTeacher(){      
        $usuario_nombre = mysqli_real_escape_string($GLOBALS['link'],$_POST['dniprofesor']);
        $usuario_clave = mysqli_real_escape_string($GLOBALS['link'],$_POST['password']);
        $nombre = mysqli_real_escape_string($GLOBALS['link'],$_POST['nombreprofesor']);
        $usuario_apellidos = mysqli_real_escape_string($GLOBALS['link'],$_POST['apellidosprofesor']);
        $usuario_email = mysqli_real_escape_string($GLOBALS['link'],$_POST['emailprofesor']);
        $result = userService::getUserByName(utf8_decode($usuario_nombre));
        if($result==FALSE){ 
                    echo 0;
        }
        else{
            if(!$row=mysqli_fetch_assoc($result)) {
                    $usuario_clave = md5($usuario_clave);
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
    
     /**
     * Function to update the group access permissions of a student.
     *
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
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
                if(!$fila=mysqli_fetch_assoc($result)){
                     $insert = groupService::insertUsuarioGrupo($idStudent, $group);
                     if(!$insert){
                         $flag = 0;
                     }
                }
            }
            else{
                if($fila=mysqli_fetch_assoc($result)){//Si hay filas -> Delete
                    $delete = groupService::deleteUserGroupByIds($group, $idStudent);
                    if(!$delete){
                         $flag = 0;
                     }
                }
            }
            $cont++;
        }
        echo $flag;
    }     
    
     /**
     * Function to delete a user from the application and from the database.
     *
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
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
    
    /**
     * Function to check if the student or the teacher can be update in the grid and if it's OK update it.
     *
     * Check that the name of the user it's not repeat and update the user information with the new data receive in the row post variable.
     * Echo 0 if if also exist an user with the same name
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function checkUpdateGridUser(){
        $row = $_POST["row"];
        $row = json_decode("$row",true);
        $idUser = $_POST['idUser'];
        
        $result = userService::checkNameNotRepeat(utf8_decode($row[4]), $idUser);
        
        if($result!=FALSE){
            if(!$fila=mysqli_fetch_assoc($result)) { 
                $result2 = userService::updateById(utf8_decode($row[1]), utf8_decode($row[2]), utf8_decode($row[3]), utf8_decode($row[4]), $idUser);
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
    
    /**
     * Function to change the password of a user.
     *
     * Echo 0 if if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function updatePassword(){
        $newPass = mysqli_real_escape_string($GLOBALS['link'],$_POST['newPass']);
        $idUser = mysqli_real_escape_string($GLOBALS['link'],$_POST['idUser']);
        $newPassEncript = md5($newPass);
        
        if($newPass!=""){
            $update = userService::updatePasswordById($newPassEncript, $idUser);
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