 <?php
    session_start();
    include('../model/acceso_db.php');
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
    
        $result = mysqli_query($GLOBALS['link'],"SELECT grupo.nombre FROM grupo WHERE grupo.nombre= '".$grupo."'");
        
        if($result!=FALSE){
            if(!$row=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otro grupo con el mismo nombre, por lo que insertamos el nuevo grupo
                $reg = mysqli_query($GLOBALS['link'],"INSERT INTO grupo (grupo.nombre, grupo.descripcion, grupo.idUsuarioCreador) VALUES ('".utf8_decode($grupo)."','".utf8_decode($descripcion)."','".$_SESSION['usuario_id']."')");
                $row = mysqli_query($GLOBALS['link'],"SELECT grupo.idGrupo FROM grupo WHERE grupo.nombre='".$grupo."'");
                if($idGrupo = mysqli_fetch_assoc($row)){
                    $idGrupo = $idGrupo['idGrupo'];
                    //Insertamos en la colección pública
                    $reg2 = mysqli_query($GLOBALS['link'],"INSERT INTO grupo_coleccion (grupo_coleccion.idGrupo, grupo_coleccion.idColeccion) VALUES ('".$idGrupo."','1')");
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
    
        $result = mysqli_query($GLOBALS['link'],"SELECT grupo.nombre FROM grupo WHERE grupo.nombre= '".$grupo."'");
        
        if($result!=FALSE){
            if(!$row=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otro grupo con el mismo nombre, por lo que insertamos el nuevo grupo
                $reg = mysqli_query($GLOBALS['link'],"INSERT INTO grupo (grupo.nombre, grupo.descripcion, grupo.idUsuarioCreador) VALUES ('".utf8_decode($grupo)."','".utf8_decode($descripcion)."','".$profesor."')");
                $row = mysqli_query($GLOBALS['link'],"SELECT grupo.idGrupo FROM grupo WHERE grupo.nombre='".$grupo."'");
                if($idGrupo = mysqli_fetch_assoc($row)){
                    $idGrupo = $idGrupo['idGrupo'];
                    //Insertamos en la colección pública
                    $reg2 = mysqli_query($GLOBALS['link'],"INSERT INTO grupo_coleccion (grupo_coleccion.idGrupo, grupo_coleccion.idColeccion) VALUES ('".$idGrupo."','1')");
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
    
    function deleteStudent(){
        $idAlumno = mysqli_real_escape_string($GLOBALS['link'],$_POST['idAlumno']);
        $idGrupo = mysqli_real_escape_string($GLOBALS['link'],$_POST['idGrupo']);
    
        $result = mysqli_query($GLOBALS['link'],"DELETE FROM usuario_grupo WHERE usuario_grupo.idUsuario= '".$idAlumno."' AND usuario_grupo.idGrupo='".$idGrupo."'");
        
        if($result!=FALSE){
                    echo 1; //Delete grupo OK
        }
        else{
            echo 0; //Error
        }

    }
?> 