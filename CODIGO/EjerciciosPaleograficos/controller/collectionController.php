<?php
    session_start();
    include('../model/persistence/collectionService.php');
    include('../model/persistence/groupService.php');
    include('../model/persistence/documentService.php');
    
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
        case 'saveDocumentAccess':
            saveDocumentAccess();
            break;

    }

    function newCollection(){
        $flag = 1;
        $collection = mysqli_real_escape_string($GLOBALS['link'],$_POST['collection']);
        $description = mysqli_real_escape_string($GLOBALS['link'],$_POST['description']);
        $groups = $_POST["groups"];
        $groups = json_decode("$groups",true);
        
        $result = collectionService::getByName(utf8_decode($collection));
        
        if($result!=FALSE){
            if(!$row=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otra coleccion con el mismo nombre, por lo que insertamos la nueva colección
                $reg = collectionService::insertCollection(utf8_decode($collection), utf8_decode($description));
                $result2=collectionService::getByName(utf8_decode($collection));
                if($reg) {
                    $idCollection=mysqli_fetch_assoc($result2);
                    $idCollection = $idCollection['idColeccion'];
                    foreach($groups as $group){
                        $reg2 = collectionService::insertGroupCollection(utf8_decode($group), utf8_decode($idCollection));
                        if(!$reg2){
                            $flag = 0;
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
        $result = collectionService::checkNameNotRepeat($row[1], $row[0]);
        
        if($result!=FALSE){
            if(!$fila=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe atra colección con el mismo nombre, por lo que actualizamos la fila
                $result2 = collectionService::updateById(utf8_decode($row[1]), utf8_decode($row[2]), $row[0]);
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
    
        $result = collectionService::deleteById($idColeccion);
        
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
    
    function saveDocumentAccess(){
        $idDocument=mysqli_real_escape_string($GLOBALS['link'],$_POST['idDocument']);
        $collections = $_POST["collections"];
        $collections = json_decode("$collections",true);
        $return = 1;
        
        //Para borrar las filas de las colecciones en las que ya no esta el documento
        $result = documentService::getColeccionDocumentoByIdDoc($idDocument);
        if($result!=FALSE){
            while($row=mysqli_fetch_assoc($result)) {
                $flag=false;
                if(in_array($row['idColeccion'],$collections)){
                    $flag = true;
                }
                if(!$flag){// si no esta la coleccion en la lista, borramos la fila porque se le ha denegado el acceso
                    $delete = documentService::deleteColleccionDocumentoByIds($row['idColeccion'], $idDocument);
                    if(!$delete){
                        $return =0;
                    }
                }// si no no hacemos nada, porque el usuario le ha dado acceso y ya lo tiene
            }
        }else{
            $return =0;
        }
        
        //Para añadir las filas de las colecciones a las que se le ha proporcionado acceso al documento ahora
         foreach($collections as $idCollection){
             $result2 = documentService::getColleccionDocumentoByIds($idDocument, $idCollection);
             if(!$fila=mysqli_fetch_assoc($result2)){//Si no hay filas -> Insert
                $insert = documentService::insertColeccionDocumento($idCollection, $idDocument);
                if(!$insert){
                    $return =0;
                }
              }        
         }
         echo $return;
     }

?> 