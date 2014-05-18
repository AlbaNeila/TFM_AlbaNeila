<?php
    session_start();
    include('../model/acceso_db.php');
    $method = $_POST['method'];
    
    switch($method){
        case 'newCollection':
            newCollection();
            break;
    }

    function newCollection(){
        $collection = mysqli_real_escape_string($GLOBALS['link'],$_POST['collection']);
        $description = mysqli_real_escape_string($GLOBALS['link'],$_POST['description']);
        $ordered = mysqli_real_escape_string($GLOBALS['link'],$_POST['ordered']);
        
        $result = mysqli_query($GLOBALS['link'],"SELECT coleccion.nombre FROM coleccion WHERE coleccion.nombre= '".$collection."'");
        
        if($result!=FALSE){
            if(!$row=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otra coleccion con el mismo nombre, por lo que insertamos la nueva colección
                $reg = mysqli_query($GLOBALS['link'],"INSERT INTO coleccion (coleccion.nombre, coleccion.descripcion, coleccion.ordenada) VALUES ('".utf8_decode($collection)."','".utf8_decode($description)."','".utf8_decode($ordered)."')");
                if($reg) {
                    echo 1; //Nuevo grupo OK
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
        $result = mysqli_query($GLOBALS['link'],"SELECT grupo.nombre FROM grupo WHERE grupo.nombre= '".$row[1]."' and grupo.idGrupo<>'".$row[0]."'");
        
        if($result!=FALSE){
            if(!$fila=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otro grupo con el mismo nombre, por lo que actualizamos la fila
                $result2 = mysqli_query($GLOBALS['link'],"UPDATE grupo SET grupo.nombre='".utf8_decode($row[1])."', grupo.descripcion='".utf8_decode($row[2])."' WHERE grupo.idGrupo='".$row[0]."'");
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
    
        $result = mysqli_query($GLOBALS['link'],"DELETE FROM grupo WHERE grupo.idGrupo= '".$idGrupo."'");
        
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
            $result = mysqli_query($GLOBALS['link'],"UPDATE usuario_grupo SET usuario_grupo.solicitud='0' WHERE usuario_grupo.idGrupo='".$idGrupo."' AND usuario_grupo.idUsuario='".$alumnos[$cont]."'");
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
            $result = mysqli_query($GLOBALS['link'],"DELETE FROM usuario_grupo WHERE usuario_grupo.idGrupo='".$idGrupo."' AND usuario_grupo.idUsuario='".$alumnos[$cont]."'");
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
?> 