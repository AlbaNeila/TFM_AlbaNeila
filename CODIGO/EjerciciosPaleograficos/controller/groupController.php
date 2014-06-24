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
    }

    function newGroup(){
        $grupo = mysqli_real_escape_string($GLOBALS['link'],$_POST['grupo']);
        $descripcion = mysqli_real_escape_string($GLOBALS['link'],$_POST['descripcion']);
    
        $result = groupService::getByName(utf8_decode($grupo));
        
        if($result!=FALSE){
            if(!$row=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otro grupo con el mismo nombre, por lo que insertamos el nuevo grupo
                $reg = groupService::insertGroup(utf8_decode($grupo), utf8_decode($descripcion), $_SESSION['usuario_id']);
                $result2= groupService::getByName(utf8_decode($grupo));
                if($idGrupo = mysqli_fetch_assoc($result2)){
                    $idGrupo = $idGrupo['idGrupo'];
                    //Insertamos en la colección pública
                    $reg2 = collectionService::insertGroupCollection($idGrupo,1);
                    if($reg && $reg2) {
                        echo 1; //Nuevo grupo OK
                    }
                }
            }
            else{
                echo 0; //Ya existe un grupo con el mismo nombre
            }
        }
    }
    
    function newGroupAdmin(){
        $grupo = mysqli_real_escape_string($GLOBALS['link'],$_POST['grupo']);
        $descripcion = mysqli_real_escape_string($GLOBALS['link'],$_POST['descripcion']);
        $profesor = mysqli_real_escape_string($GLOBALS['link'],$_POST['profesor']);
    
        $result = groupService::getByName(utf8_decode($grupo));
        
        if($result!=FALSE){
            if(!$row=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otro grupo con el mismo nombre, por lo que insertamos el nuevo grupo
                $reg = groupService::insertGroup(utf8_decode($grupo), utf8_decode($descripcion), $profesor);
                $row = groupService::getByName(utf8_decode($grupo));
                if($idGrupo = mysqli_fetch_assoc($row)){
                    $idGrupo = $idGrupo['idGrupo'];
                    //Insertamos en la colección pública
                    $reg2 = collectionService::insertGroupCollection($idGrupo,1);
                    if($reg && $reg2) {
                        echo 1; //Nuevo grupo OK
                    }
                }
            }
            else{
                echo 0; //Ya existe un grupo con el mismo nombre
            }
        }
    }
    
    function checkUpdateGrid(){
        $row = $_POST["row"];
        $row = json_decode("$row",true);
        $result = groupService::checkNameNotRepeat($row[1], $row[0]);
        
        if($result!=FALSE){
            if(!$fila=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otro grupo con el mismo nombre, por lo que actualizamos la fila
                $result2 = groupService::updateById(utf8_decode($row[1]), utf8_decode($row[2]), utf8_decode($row[0]));
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
    
    function deleteGroup(){
        $idGrupo = mysqli_real_escape_string($GLOBALS['link'],$_POST['grupo']);
    
        $result = groupService::deleteById($idGrupo);
        
        if($result!=FALSE){
                    echo 1; //Delete grupo OK
        }
        else{
            echo 0; //Error
        }

    }
    
    function acceptRequest(){
        $idGrupo = $_POST["idGrupo"];
        $alumnos = $_POST["alumnos"];
        $alumnos = json_decode("$alumnos",true);
        
        $flag=true;
        
       for($cont=0; $cont < count($alumnos);$cont++){
            $result = groupService::updateUsuarioGrupoAccess($idGrupo, $alumnos[$cont]);
            if(!$result){
                $flag = false;
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
        $alumnos = $_POST["alumnos"];
        $alumnos = json_decode("$alumnos",true);
        
        $flag=true;
        
       for($cont=0; $cont < count($alumnos);$cont++){
            $result = groupService::deleteUsuarioGrupoByIds($idGrupo, $alumnos[$cont]);
            $result2 = userService::deleteById($alumnos[$cont]);
            if(!$result || !$result2){
                $flag = false;
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
    
        $result = groupService::deleteUsuarioGrupoByIds($idGrupo, $idAlumno);
        
        if($result!=FALSE){
                    echo 1; //Delete grupo OK
        }
        else{
            echo 0; //Error
        }

    }
?> 