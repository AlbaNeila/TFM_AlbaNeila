<?php
    session_start();
    include('../model/persistence/groupService.php');
    include('../model/persistence/collectionService.php');
    include('../model/persistence/userService.php');
    
    
    $method = $_POST['method'];
    
    switch($method){
        case 'newGroup':
            newGroup();
            break;
        case 'newGroupAdmin':
            newGroupAdmin();
            break;
        case 'checkUpdateGrid':
            checkUpdateGrid();
            break;
        case 'deleteGroup':
            deleteGroup();
            break;
        case 'acceptRequest':
            acceptRequest();
            break;
        case 'rejectRequest':
            rejectRequest();
            break;
        case 'deleteStudent':
            deleteStudent();
            break;
        case 'requestAccess':
            requestAccess();
            break;
    }

    /**
     * Function to add a new Group.
     *
     * Insert the new group in the database and update the permissions to access to the public collection.
     * Echo 0 if error
     * Echo 1 if ok
     * Echo 2 if if also exist an group with the same name
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version  1.0
     *  @access   public
     *  @return void
     */
    function newGroup(){
        $grupo = mysqli_real_escape_string($GLOBALS['link'],$_POST['grupo']);
        $descripcion = mysqli_real_escape_string($GLOBALS['link'],$_POST['descripcion']);
    
        $existGroup = groupService::getByName(utf8_decode($grupo));

            if(!$existGroup) { 
                $idGroup = groupService::insertGroup(utf8_decode($grupo), utf8_decode($descripcion), $_SESSION['usuario_id']);
                if($idGroup != null){
                    $reg2 = collectionService::insertGroupCollection($idGroup,1);
                    if($reg2) {
                        echo 1;
                    }else{
                        echo 2;
                    }
                }else{
                    echo 2;
                }
            }
            else{
                echo 0;
            }

    }
    
    /**
     * Function to add a new Group by the administrator.
     *
     * Insert the new group in the database and update the permissions to access to the public collection.
     * Echo 0 if error
     * Echo 1 if ok
     * Echo 2 if if also exist an group with the same name
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version  1.0
     *  @access   public
     *  @return void
     */
    function newGroupAdmin(){
        $grupo = mysqli_real_escape_string($GLOBALS['link'],$_POST['grupo']);
        $descripcion = mysqli_real_escape_string($GLOBALS['link'],$_POST['descripcion']);
        $profesor = mysqli_real_escape_string($GLOBALS['link'],$_POST['profesor']);
    
        $existGroup = groupService::getByName(utf8_decode($grupo));
        
            if(!$existGroup) { 
                $idGroup = groupService::insertGroup(utf8_decode($grupo), utf8_decode($descripcion), $profesor);
                if($idGroup != null){
                    $reg2 = collectionService::insertGroupCollection($idGroup,1);
                    if($reg2) {
                        echo 1;
                    }
                }
            }
            else{
                echo 0;
            }
    }
    
    /**
     * Function to check if the Group can be update and if it's OK update it.
     *
     * Check that the name of the group it's not repeat and update the Group information with the new data receive in the row post variable.
     * Echo 0 if if also exist a group with the same name
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function checkUpdateGrid(){
        $row = $_POST["row"];
        $row = json_decode("$row",true);
        $existGroup = groupService::checkNameNotRepeat($row[1], $row[0]);
        
        if(!$existGroup){
//Si no hay filas es que no existe otro grupo con el mismo nombre, por lo que actualizamos la fila
            $insert = groupService::updateById(utf8_decode($row[1]), utf8_decode($row[2]), utf8_decode($row[0]));
            if($insert!=FALSE){
                echo 1;
            }else{
                echo 0;
            }
        }
        else{
            echo 0;
        }
    }
    
    /**
     * Function to delete a Group.
     *
     * Delete a group from the data base with the id group received in the 'grupo' post variable.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function deleteGroup(){
        $idGrupo = mysqli_real_escape_string($GLOBALS['link'],$_POST['grupo']);
    
        $delete = groupService::deleteById($idGrupo);
        
        if($delete!=FALSE){
                    echo 1; //Delete grupo OK
        }
        else{
            echo 0; //Error
        }

    }
    
     /**
     * Function to accept the group access request of students.
     *
     * Accept the group access request of an array of students updating the 'solicitud' value to 0 in the usuario_grupo table and sending an email to notificate.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function acceptRequest(){
        $idGrupo = $_POST["idGrupo"];
        $nameGroup = $_POST["nameGroup"];
        $alumnos = $_POST["alumnos"];
        $alumnos = json_decode("$alumnos",true);
        
        $flag=true;
        
       for($cont=0; $cont < count($alumnos);$cont++){
            $update = groupService::updateUserGroupAccess($idGrupo, $alumnos[$cont]);
            if(!$update){
                $flag = false;
            }else{
                $user = userService::getUserById($alumnos[$cont]);
                if($user!=null){
                    $subject = "UBUPal: Your group request has been accepted";
                    $message = "Hello, now you can access the group: ".$nameGroup." because the teacher has accepted your request. \n Regards.";
                    $headers = 'From: ubupal@ubu.es' . "\r\n";                
                    mail($user->getEmail(), $subject, $message, $headers);
                }
            }
        }
       if($flag){
        echo 1;
       }
       else {
        echo 0;   
       }
    }
    
     /**
     * Function to deny the group access request of students.
     *
     * Deny the group access request of an array of students removing them from the usuario_grupo table and sending an email to notificate.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function rejectRequest(){
        $idGrupo = $_POST["idGrupo"];
        $nameGroup = $_POST["nameGroup"];
        $alumnos = $_POST["alumnos"];
        $alumnos = json_decode("$alumnos",true);
        
        $flag=true;
        
       for($cont=0; $cont < count($alumnos);$cont++){
            $deleteUserGroup = groupService::deleteUserGroupByIds($idGrupo, $alumnos[$cont]);
            $update = groupService::updateUserGroupAccess($idGrupo, $alumnos[$cont]);
            
            if(!$deleteUserGroup || !$update){
                $flag = false;
            }else{
                $user = userService::getUserById($alumnos[$cont]);
                if($user!=null){
                    $subject = "UBUPal: Your group request has been rejected";
                    $message = "Hello, the teacher hasn't accepted your request to access the group: ".$nameGroup."\n Regards.";
                    $headers = 'From: ubupal@ubu.es' . "\r\n";                
                    mail($user->getEmail(), $subject, $message, $headers);
                }
            }
        }
       if($flag){
        echo 1;
       }
       else {
        echo 0;   
       }
    }

    /**
     * Function to remove a student from a group.
     *
     * Deny the group access of a student form the id group received in the idGrupo post variable.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function deleteStudent(){
        $idAlumno = mysqli_real_escape_string($GLOBALS['link'],$_POST['idAlumno']);
        $idGrupo = mysqli_real_escape_string($GLOBALS['link'],$_POST['idGrupo']);
    
        $result = groupService::deleteUserGroupByIds($idGrupo, $idAlumno);
        
        if($result!=FALSE){
                    echo 1; 
        }
        else{
            echo 0;
        }
    }
    
    /**
     * Function to request access to a group.
     *
     * Send a request to access to the group with the id gropup received in the 'idGroup' post variable.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function requestAccess(){
        $idGroup = mysqli_real_escape_string($GLOBALS['link'],$_POST['idGroup']);
        
        $insert = groupService::insertUserGroupRequest($_SESSION['usuario_id'], $idGroup);
        if($insert){
            echo 1;
        }else{
            echo 0;
        }
    }
?> 