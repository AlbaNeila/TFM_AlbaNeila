 <?php
    session_start();
    include('../model/acceso_db.php');
    $method = $_POST['method'];
    
    switch($method){
        case 'deleteDoc':
            deleteDoc();
            break;
        case 'checkUpdateGrid':
            checkUpdateGrid();
            break;
        case 'checkNameDocument':
            checkNameDocument();
            break;
        case 'updatePermissionsGroup':
            updatePermissionsGroup();
            break;
    }
    
    function deleteDoc(){
        $idDoc = mysqli_real_escape_string($GLOBALS['link'],$_POST['idDoc']);
        
        $result2 = mysqli_query($GLOBALS['link'],"SELECT documento.imagen,documento.transcripcion FROM documento WHERE documento.idDocumento= '".$idDoc."'");
        if($result2!=FALSE){
            $row=mysqli_fetch_assoc($result2);
            $imagen = $row['imagen'];
            $transcripcion = $row['transcripcion'];
            unlink($imagen);
            unlink($transcripcion);
            $result = mysqli_query($GLOBALS['link'],"DELETE FROM documento WHERE documento.idDocumento= '".$idDoc."'");
            if($result!=FALSE){
                 echo 1; //Delete document OK
            }
        }
        else{
            echo 0; //Error
        }

    }
    
    function checkNameDocument(){
        $document = $_POST['document'];
        $result = mysqli_query($GLOBALS['link'],"SELECT documento.nombre FROM documento WHERE documento.nombre= '".utf8_decode($document)."'");
        
        if($result!=FALSE){
            if(!$row=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otro documento con el mismo nombre
                echo 1;
            }
            else{
                echo 2;
            }
        }else{
            echo 0;
        }
        
    }
    
    function checkUpdateGrid(){
        $row = $_POST["row"];
        $row = json_decode("$row",true);
        $idDocument = $_POST['idDoc'];
        
        $result = mysqli_query($GLOBALS['link'],"SELECT documento.nombre FROM documento WHERE documento.nombre= '".$row[0]."' and documento.idDocumento<>'".$idDocument."'");
        
        if($result!=FALSE){
            if(!$fila=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otro documento con el mismo nombre, por lo que actualizamos la fila
                $result2 = mysqli_query($GLOBALS['link'],"UPDATE documento SET documento.nombre='".utf8_decode($row[0])."', documento.descripcion='".utf8_decode($row[1])."',documento.fecha='".utf8_decode($row[3])."' ,documento.tipoEscritura='".utf8_decode($row[2])."' WHERE documento.idDocumento='".$idDocument."'");
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

    function updatePermissionsGroup(){
        $groups = $_POST["groups"];
        $groups= json_decode("$groups",true);
        $permissions = $_POST["permissions"];
        $permissions= json_decode("$permissions",true);
        $idCollection = $_POST['idCollection'];
        
        $cont=0;
        $flag=1;
        
        foreach($groups as $group){
            $result = mysqli_query($GLOBALS['link'],"SELECT grupo_coleccion.idGrupo FROM grupo_coleccion WHERE grupo_coleccion.idGrupo= '".$group."' and grupo_coleccion.idColeccion='".$idCollection."'");
            if($permissions[$cont]==true){
                if(!$fila=mysqli_fetch_assoc($result)){//Si no hay filas -> Insert
                     $insert = mysqli_query($GLOBALS['link'],"INSERT INTO grupo_coleccion (grupo_coleccion.idGrupo, grupo_coleccion.idColeccion) VALUES ('".$group."','".$idCollection."')");
                     if(!$insert){
                         $flag = 0;
                     }
                }
            }
            else{
                if($fila=mysqli_fetch_assoc($result)){//Si hay filas -> Delete
                    $delete = mysqli_query($GLOBALS['link'],"DELETE FROM grupo_coleccion WHERE grupo_coleccion.idGrupo= '".$group."' AND grupo_coleccion.idColeccion='".$idCollection."'");
                    if(!$delete){
                         $flag = 0;
                     }
                }
            }
            $cont++;
        }
        echo $flag;
    }
    
?> 