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
    
    /**
     * Function to delete a Document.
     *
     * Delete a document form the data base with the id document received in the idDoc post variable.
     * Also delete the image and the transcription files from the img_xml folder.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function deleteDoc(){
        $idDoc = mysqli_real_escape_string($GLOBALS['link'],$_POST['idDoc']);
        
        $document = documentService::getById($idDoc);
        if($document!=null){
            $imagen = $document->getImageDocument();
            $transcripcion = $document->getTranscriptionDocument();
            try{
            unlink($imagen);
            unlink($transcripcion);
            }catch (Exception $e) {
                echo 'Excepción eliminar documentos: ',  $e->getMessage(), "\n";
            }
            $delete = documentService::deleteById($idDoc);
            if($delete!=FALSE){
                 echo 1;
            }
        }
        else{
            echo 0;
        }

    }
    
    /**
     * Function to check if the name document is already exist.
     *
     * Check if the name received in the document post variable is repeat.
     * Echo 1 if the document name is not repeat.
     * Echo 2 if the document name is repeat.
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function checkNameDocument(){
        $document = $_POST['document'];
        $existDocument = documentService::getByName($document);
        

        if(!$existDocument) {
            echo 1;
        }
        else{
            echo 2;
        }
    }
    
    /**
     * Function to check if the Document can be update and if it's OK update it.
     *
     * Check that the name of the document it's not repeat and update the Document information with the new data receive in the row post variable.
     * Echo 0 if if also exist a document with the same name
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
        $idDocument = $_POST['idDoc'];
        
        $repeatDocument = documentService::checkNameNotRepeat($row[0], $idDocument);

            if(!$repeatDocument) {
                $update = documentService::updateById($idDocument,utf8_decode($row[0]),utf8_decode($row[1]),utf8_decode($row[3]),utf8_decode($row[2]));
                if($update!=FALSE)
                    echo 1;
            }
            else{
                echo 0;
            }
    }

    /**
     * Function to update the group permissions to access a collection.
     *
     * Update the grupo_coleccion table with the new permissions and with the id of collection received in the idCollection post variable.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function updatePermissionsGroup(){
        $groups = $_POST["groups"];
        $groups= json_decode("$groups",true);
        $permissions = $_POST["permissions"];
        $permissions= json_decode("$permissions",true);
        $idCollection = $_POST['idCollection'];
        
        $cont=0;
        $flag=1;
        
        foreach($groups as $group){
            $groupCollection = collectionService::getGroupCollectionByIds($group, $idCollection);
            if($permissions[$cont]==true){
                if(!$groupCollection){
                     $insert = collectionService::insertGroupCollection($group, $idCollection);
                     if(!$insert){
                         $flag = 0;
                     }
                }
            }
            else{
                if($groupCollection){
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