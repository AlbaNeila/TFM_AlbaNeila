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
    
    /**
     * Function to add a new Collection.
     *
     * Insert the new collection in the database and update the grupo_coleccion table with the permissions of the groups received in the groups post variable.
     * Echo 0 if error
     * Echo 1 if ok
     * Echo 2 if if also exist a collection with the same name
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version  1.0
     *  @access   public
     *  @return void
     */
    function newCollection(){
        $flag = 1;
        $collection = mysqli_real_escape_string($GLOBALS['link'],$_POST['collection']);
        $description = mysqli_real_escape_string($GLOBALS['link'],$_POST['description']);
        $groups = $_POST["groups"];
        $groups = json_decode("$groups",true);
        
        $existCollection = collectionService::getByName(utf8_decode($collection));
        

        if(!$existCollection) { 
            $idCollection = collectionService::insertCollection(utf8_decode($collection), utf8_decode($description));
            if($idCollection!=null) {
                foreach($groups as $group){
                    $insert = collectionService::insertGroupCollection(utf8_decode($group), utf8_decode($idCollection));
                    if(!$insert){
                        $flag = 0;
                    }
                }
            }
            else{
                $flag = 0;
            }
        }
        else{
            $flag = 2; 
        }        

        echo $flag;
    }
    
    /**
     * Function to check if the Collection can be update and if it's OK update it.
     *
     * Check that the name of the collection it's not repeat and update the Collection information with the new data receive in the row post variable.
     * Echo 0 if if also exist a collection with the same name
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
        $repeatCollection = collectionService::checkNameNotRepeat($row[1], $row[0]);
        
        if(!$repeatCollection) { 
            $update = collectionService::updateById(utf8_decode($row[1]), utf8_decode($row[2]), $row[0]);
            if($update!=FALSE)
                echo 1;
        }
        else{
            echo 0;
        }

    }
    
    /**
     * Function to delete a Collection.
     *
     * Delete a collection from the data base with the id collection received in the coleccion post variable.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function deleteCollection(){
        $idColeccion = mysqli_real_escape_string($GLOBALS['link'],$_POST['coleccion']);
    
        $delete = collectionService::deleteById($idColeccion);
        
        if($delete!=FALSE){
                    echo 1; 
        }
        else{
            echo 0; 
        }

    }
    
    /**
     * Function to accept the group access request of students.
     *
     * Accept the group access request of an array of students updating the solicitud value to 0 in the usuario_grupo table.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function acceptRequest(){
        $idGrupo = $_POST["idGrupo"];
        $alumnos = $_POST["alumnos"];
        $alumnos = json_decode("$alumnos",true);
        
        $flag=true;
        
       for($cont=0; $cont < count($alumnos);$cont++){
            $update = groupService::updateUserGroupAccess($idGrupo, $alumnos[$cont]);
            if(!$update){
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
    
    /**
     * Function to reject the group access request of students.
     *
     * Reject the group access request of an array of students removing them from the usuario_grupo table.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function rejectRequest(){
        $idGrupo = $_POST["idGrupo"];
        $alumnos = $_POST["alumnos"];
        $alumnos = json_decode("$alumnos",true);
        
        $flag=true;
        
       for($cont=0; $cont < count($alumnos);$cont++){
            $delete = groupService::deleteUserGroupByIds($idGrupo, $alumnos[$cont]);
            if(!$delete){
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
    
    /**
     * Save the access to a document from collections.
     *
     * Update the coleccion_documento table with the collections received in the collections post variable and the id of the document received in the idDocument post variable.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function saveDocumentAccess(){
        $idDocument=mysqli_real_escape_string($GLOBALS['link'],$_POST['idDocument']);
        $collections = $_POST["collections"];
        $collections = json_decode("$collections",true);
        $return = 1;
        
        $result = documentService::getColeccionDocumentoByIdDoc($idDocument);
        if($result!=FALSE){
            while($row=mysqli_fetch_assoc($result)) {
                $flag=false;
                if(in_array($row['idColeccion'],$collections)){
                    $flag = true;
                }
                if(!$flag){
                    $delete = documentService::deleteColleccionDocumentoByIds($row['idColeccion'], $idDocument);
                    if(!$delete){
                        $return =0;
                    }
                }
            }
        }else{
            $return =0;
        }
        
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