<?php
    session_start();
    include('../model/persistence/documentService.php');
    include('../model/persistence/collectionService.php');
    
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
        
        $result2 = documentService::getById($idDoc);
        if($result2!=FALSE){
            $row=mysqli_fetch_assoc($result2);
            $imagen = $row['imagen'];
            $transcripcion = $row['transcripcion'];
            unlink($imagen);
            unlink($transcripcion);
            $result = documentService::deleteById($idDoc);
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
        $result = documentService::getByName($document);
        
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
        
        $result = documentService::checkNameNotRepeat($row[0], $idDocument);
        
        if($result!=FALSE){
            if(!$fila=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otro documento con el mismo nombre, por lo que actualizamos la fila
                $result2 = documentService::updateById($idDocument,utf8_decode($row[0]),utf8_decode($row[1]),utf8_decode($row[3]),utf8_decode($row[2]));
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
            $result = collectionService::getGroupCollectionByIds($group, $idCollection);
            if($permissions[$cont]==true){
                if(!$fila=mysqli_fetch_assoc($result)){//Si no hay filas -> Insert
                     $insert = collectionService::insertGroupCollection($group, $idCollection);
                     if(!$insert){
                         $flag = 0;
                     }
                }
            }
            else{
                if($fila=mysqli_fetch_assoc($result)){//Si hay filas -> Delete
                    $delete = collectionService::deleteGroupCollectionByIds($group, $idCollection);
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