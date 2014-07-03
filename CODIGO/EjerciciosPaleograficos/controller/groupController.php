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

    function newGroup(){
        $grupo = mysqli_real_escape_string($GLOBALS['link'],$_POST['grupo']);
        $descripcion = mysqli_real_escape_string($GLOBALS['link'],$_POST['descripcion']);
    
        $existGroup = groupService::getByName(utf8_decode($grupo));
        

            if(!$existGroup) { //Si no hay filas es que no existe otro grupo con el mismo nombre, por lo que insertamos el nuevo grupo
                $idGroup = groupService::insertGroup(utf8_decode($grupo), utf8_decode($descripcion), $_SESSION['usuario_id']);
                if($idGroup != null){
                    //Insertamos en la colección pública
                    $reg2 = collectionService::insertGroupCollection($idGroup,1);
                    if($reg && $reg2) {
                        echo 1; //Nuevo grupo OK
                    }else{
                        echo 2;
                    }
                }else{
                    echo 2;
                }
            }
            else{
                echo 0; //Ya existe un grupo con el mismo nombre
            }

    }
    
    function newGroupAdmin(){
        $grupo = mysqli_real_escape_string($GLOBALS['link'],$_POST['grupo']);
        $descripcion = mysqli_real_escape_string($GLOBALS['link'],$_POST['descripcion']);
        $profesor = mysqli_real_escape_string($GLOBALS['link'],$_POST['profesor']);
    
        $existGroup = groupService::getByName(utf8_decode($grupo));
        
            if(!$existGroup) { //Si no hay filas es que no existe otro grupo con el mismo nombre, por lo que insertamos el nuevo grupo
                $idGroup = groupService::insertGroup(utf8_decode($grupo), utf8_decode($descripcion), $profesor);
                if($idGroup != null){
                    //Insertamos en la colección pública
                    $reg2 = collectionService::insertGroupCollection($idGroup,1);
                    if($reg2) {
                        echo 1; //Nuevo grupo OK
                    }
                }
            }
            else{
                echo 0; //Ya existe un grupo con el mismo nombre
            }
    }
    
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
                    $subject = "UBUPal: Your request group has been accepted";
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
                    $subject = "UBUPal: Your request group has been rejected";
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
    
    function deleteStudent(){
        $idAlumno = mysqli_real_escape_string($GLOBALS['link'],$_POST['idAlumno']);
        $idGrupo = mysqli_real_escape_string($GLOBALS['link'],$_POST['idGrupo']);
    
        $result = groupService::deleteUserGroupByIds($idGrupo, $idAlumno);
        
        if($result!=FALSE){
                    echo 1; //Delete grupo OK
        }
        else{
            echo 0; //Error
        }
    }
    
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