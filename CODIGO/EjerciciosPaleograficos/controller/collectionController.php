<?php
    session_start();
    include('../model/acceso_db.php');
    $method = $_POST['method'];
    
    switch($method){
        case 'newCollection':
            newCollection();
            break;
        case 'checkUpdateGrid':
            checkUpdateGrid();
            break;
        case 'deleteCollection':
            deleteCollection();
            break;
    }

    function newCollection(){
        $flag = 1;
        $collection = mysqli_real_escape_string($GLOBALS['link'],$_POST['collection']);
        $description = mysqli_real_escape_string($GLOBALS['link'],$_POST['description']);
        $ordered = mysqli_real_escape_string($GLOBALS['link'],$_POST['ordered']);
        $groups = $_POST["groups"];
        $groups = json_decode("$groups",true);
        
        $result = mysqli_query($GLOBALS['link'],"SELECT coleccion.nombre FROM coleccion WHERE coleccion.nombre= '".$collection."'");
        
        if($result!=FALSE){
            if(!$row=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otra coleccion con el mismo nombre, por lo que insertamos la nueva colección
                $reg = mysqli_query($GLOBALS['link'],"INSERT INTO coleccion (coleccion.nombre, coleccion.descripcion, coleccion.ordenada) VALUES ('".utf8_decode($collection)."','".utf8_decode($description)."','".utf8_decode($ordered)."')");
                if($reg) {
                    $result4 = mysqli_query($GLOBALS['link'],"SELECT coleccion.idColeccion FROM coleccion WHERE coleccion.nombre='".utf8_decode($collection)."'");
                    $idCollection=mysqli_fetch_assoc($result4);
                    $idCollection = $idCollection['idColeccion'];
                    foreach($groups as $group){
                        $result3 = mysqli_query($GLOBALS['link'],"SELECT grupo.idGrupo FROM grupo WHERE grupo.nombre='".utf8_decode($group)."'");
                        if($result3!=FALSE){ //Tenemos el idGrupo del grupo que tiene acceso a la colección
                            if($row=mysqli_fetch_assoc($result3)) {
                                $idGrupo = $row['idGrupo'];
                                $reg2 = mysqli_query($GLOBALS['link'],"INSERT INTO grupo_coleccion (grupo_coleccion.idGrupo, grupo_coleccion.idColeccion) VALUES ('".utf8_decode($idGrupo)."','".utf8_decode($idCollection)."')");
                                if(!$reg2){
                                    $flag = 0;
                                }
                            }
                        }
                    }
                }
                else{
                    $flag = 0;
                }
            }
            else{
                $flag = 2; //Ya existe un grupo con el mismo nombre
            }        
        }
        echo $flag;
    }
    
    function checkUpdateGrid(){
        $row = $_POST["row"];
        $row = json_decode("$row",true);
        $result = mysqli_query($GLOBALS['link'],"SELECT coleccion.nombre FROM coleccion WHERE coleccion.nombre= '".$row[1]."' and coleccion.idColeccion<>'".$row[0]."'");
        
        if($result!=FALSE){
            if(!$fila=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe atra colección con el mismo nombre, por lo que actualizamos la fila
                $result2 = mysqli_query($GLOBALS['link'],"UPDATE coleccion SET coleccion.nombre='".utf8_decode($row[1])."', coleccion.descripcion='".utf8_decode($row[2])."',coleccion.ordenada='".utf8_decode($row[5])."' WHERE coleccion.idColeccion='".$row[0]."'");
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
    
    function deleteCollection(){
        $idColeccion = mysqli_real_escape_string($GLOBALS['link'],$_POST['coleccion']);
    
        $result = mysqli_query($GLOBALS['link'],"DELETE FROM coleccion WHERE coleccion.idColeccion= '".$idColeccion."'");
        
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