 <?php
    session_start();
    include('../model/acceso_db.php');
    $method = $_POST['method'];
    
    switch($method){
        case 'newGroup':
            newGroup();
            break;
        case 'checkUpdateGrid':
            checkUpdateGrid();
            break;
    }

    function newGroup(){
        $grupo = mysqli_real_escape_string($GLOBALS['link'],$_POST['grupo']);
        $descripcion = mysqli_real_escape_string($GLOBALS['link'],$_POST['descripcion']);
    
        $result = mysqli_query($GLOBALS['link'],"SELECT grupo.nombre FROM grupo WHERE grupo.nombre= '".$grupo."'");
        
        if($result!=FALSE){
            if(!$row=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otro grupo con el mismo nombre, por lo que insertamos el nuevo grupo
                $reg = mysqli_query($GLOBALS['link'],"INSERT INTO grupo (grupo.nombre, grupo.descripcion, grupo.idUsuarioCreador) VALUES ('".utf8_decode($grupo)."','".utf8_decode($descripcion)."','".$_SESSION['usuario_id']."')");
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
?> 