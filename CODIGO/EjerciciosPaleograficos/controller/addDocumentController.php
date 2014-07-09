<?php
    ob_start();
    session_start();
    include('../model/persistence/documentService.php');
    
    $method = $_REQUEST['method'];
    
    switch($method){
        case 'addNewDocs':
            addNewDocs();
            break;
        case 'addNewDocsAdmin':
            addNewDocsAdmin();
            break;
        case 'changeDocs':
            changeDocs();
            break;
        case 'changeDocsAdmin':
            changeDocsAdmin();
            break;
        case 'accessDoc':
            accessDoc();
            break;
    }
    
    /**
    * Function to add a new document to one or more collection by a lecturer.
    *
    * Insert the new document in the database and upload the image and the transcription to the img_xml folder.
    * Also will update the coleccion_documento table with the collections received in the idColeccion post variable.
    * If the upload fails will redirect to an error page, else will reload the actual page.
    *
    *  @author Alba Neila Neila <ann0005@alu.ubu.es>
    *  @package controller
    *  @version  1.0
    *  @access   public
    *  @return void
    */
    function addNewDocs(){
        $coleccion = $_POST['coleccion'];
        $idColeccion = $_POST['idColeccion'];
        $flag=true;
        
        $col = $_POST['idHidden'];
        $coleccionesArray = preg_split('/[;]+/',$col);
        array_pop($coleccionesArray);
        
        if($_FILES['imagen']['size'] > 0 && $_FILES['transcripcion']['size'] > 0)
        {
            $name = mysqli_real_escape_string($GLOBALS['link'],$_POST['name']);
            $description = mysqli_real_escape_string($GLOBALS['link'],$_POST['description']);
            $type = mysqli_real_escape_string($GLOBALS['link'],$_POST['type']);
            $date = mysqli_real_escape_string($GLOBALS['link'],$_POST['date']); 
        
            //Insertamos en la BD
            $idDoc = documentService::insertDocument(utf8_decode($name), utf8_decode($description), utf8_decode($date), utf8_decode($type));
            if($idDoc!=null) {
                $uploaddir = '../img_xml/';
                
                $nameimg  = basename($_FILES['imagen']['name']);
                $extension = pathinfo($nameimg, PATHINFO_EXTENSION);
                $newnameimg       = $coleccionesArray[0].'_'.$idDoc.'.'.$extension;
                
                $namexml  = basename($_FILES['transcripcion']['name']);
                $extension = pathinfo($namexml, PATHINFO_EXTENSION);
                $newnamexml       = $coleccionesArray[0].'_'.$idDoc.'.'.$extension;
                
                $uploadimg = $uploaddir . $newnameimg;
                $uploadxml = $uploaddir . $newnamexml;
                if(!move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadimg)){
                    $flag = false;
                }
                if(!move_uploaded_file($_FILES['transcripcion']['tmp_name'], $uploadxml)){
                    $flag = false;
                }
                if(!$flag){
                    documentService::deleteById($idDoc);
                    header("Location: ../view/errorUploadDocument.php");
                }else{
                    $update = documentService::updateFilesById($idDoc, utf8_decode($uploadimg), utf8_decode($uploadxml));
                    for($i = 0;$i<count($coleccionesArray);$i++){
                        $insert2 = documentService::insertColeccionDocumento($coleccionesArray[$i], $idDoc);
                    }  
                    header("Location: ../view/documentTeacher.php?coleccion=$coleccion+&idColeccion=$idColeccion");
                }              
            }
        }else{
            header("Location: ../view/errorUploadDocument.php");
        }
        
    }
    
    /**
     * Function to add a new document to one or more collection by the administrator.
     *
     * Insert the new document in the database and upload the image and the transcription to the img_xml folder.
     * Also will update the coleccion_documento table with the collections received in the idColeccion post variable.
     * If the upload fails will redirect to an error page, else will reload the actual page.
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version  1.0
     *  @access   public
     *  @return void
     */
    function addNewDocsAdmin(){
        $col = $_POST['idHidden'];
        $coleccionesArray = preg_split('/[;]+/',$col);
        array_pop($coleccionesArray);
        $flag=true;
        
        if($_FILES['imagen']['size'] > 0 && $_FILES['transcripcion']['size'] > 0)
        {
            $name = mysqli_real_escape_string($GLOBALS['link'],$_POST['name']);
            $description = mysqli_real_escape_string($GLOBALS['link'],$_POST['description']);
            $type = mysqli_real_escape_string($GLOBALS['link'],$_POST['type']);
            $date = mysqli_real_escape_string($GLOBALS['link'],$_POST['date']); 
        
            //Insertamos en la BD
            $idDoc = documentService::insertDocument(utf8_decode($name), utf8_decode($description), utf8_decode($date), utf8_decode($type));
            if($idDoc!=null) {                    
                $uploaddir = '../img_xml/';
                
                $nameimg  = basename($_FILES['imagen']['name']);
                $extension = pathinfo($nameimg, PATHINFO_EXTENSION);
                $newnameimg       = $coleccionesArray[0].'_'.$idDoc.'.'.$extension;
                
                $namexml  = basename($_FILES['transcripcion']['name']);
                $extension = pathinfo($namexml, PATHINFO_EXTENSION);
                $newnamexml       = $coleccionesArray[0].'_'.$idDoc.'.'.$extension;
                
                $uploadimg = $uploaddir . $newnameimg;
                $uploadxml = $uploaddir . $newnamexml;
                
                if(!move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadimg)){
                    $flag = false;
                }
                if(!move_uploaded_file($_FILES['transcripcion']['tmp_name'], $uploadxml)){
                    $flag = false;
                }
                if(!$flag){
                    documentService::deleteById($idDoc);
                    header("Location: ../view/errorUploadDocument.php");
                }else{
                    $update = documentService::updateFilesById($idDoc, utf8_decode($uploadimg), utf8_decode($uploadxml));
                    for($i = 0;$i<count($coleccionesArray);$i++){
                        $insert2 = documentService::insertColeccionDocumento($coleccionesArray[$i], $idDoc);
                    }
                    header("Location: ../view/documentAdmin.php");
                }
            }
        }else{
            header("Location: ../view/errorUploadDocument.php");
        }
        
    }
    
    /**
     * Function to update the image and the translation of a document by a lecturer.
     *
     * If the function receive the two files will update the image and the transcription of the document with the id received in the idDoc post variable.
     * At the end will reload the actual page.
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version  1.0
     *  @access   public
     *  @return void
     */
    function changeDocs(){
        if($_FILES['changeimagen']['size'] > 0 && $_FILES['changetranscripcion']['size'] > 0){
            $idDocument = $_POST['idDoc'];
            $idColeccion = $_POST['idColeccion'];
            $coleccion = $_POST['coleccion'];

            $result = documentService::getFilesById($idDocument);
            if($result!=FALSE){
                $row=mysqli_fetch_assoc($result);
                $imagen = $row['imagen'];
                $transcripcion = $row['transcripcion'];

                $uploaddir = '../img_xml/';
                    
                $nameimg  = basename($_FILES['changeimagen']['name']);
                $extension = pathinfo($nameimg, PATHINFO_EXTENSION);
                $newnameimg       = $idColeccion.'_'.$idDocument.'.'.$extension;
                
                $namexml  = basename($_FILES['changetranscripcion']['name']);
                $extension = pathinfo($namexml, PATHINFO_EXTENSION);
                $newnamexml       = $idColeccion.'_'.$idDocument.'.'.$extension;
                
                $uploadimg = $uploaddir . $newnameimg;
                $uploadxml = $uploaddir . $newnamexml;
                move_uploaded_file($_FILES['changeimagen']['tmp_name'], $uploadimg);
                move_uploaded_file($_FILES['changetranscripcion']['tmp_name'], $uploadxml);
            
                $update = documentService::updateFilesById($idDocument, utf8_decode($uploadimg), utf8_decode($uploadxml));
                if($update!=FALSE){
                    //Delete old files
                    try{
                    unlink($imagen);
                    unlink($transcripcion);
                    }catch (Exception $e) {
                        echo 'Excepción eliminar documentos: ',  $e->getMessage(), "\n";
                    }
                    header("Location: ../view/documentTeacher.php?coleccion=$coleccion+&idColeccion=$idColeccion");
                }
                
            }
        }
    }
    
    /**
     * Function to update the image and the translation of a document by the administrator.
     *
     * If the function receive the two files will update the image and the transcription of the document with the id received in the idDoc post variable.
     * At the end will reload the actual page. 
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version  1.0
     *  @access   public
     *  @return void
     */
    function changeDocsAdmin(){
        if($_FILES['changeimagen']['size'] > 0 && $_FILES['changetranscripcion']['size'] > 0){
            $idDocument = $_POST['idDoc'];


            $result = documentService::getFilesCollectionById($idDocument);
            if($result!=FALSE){
                $row=mysqli_fetch_assoc($result);
                $imagen = $row['imagen'];
                $transcripcion = $row['transcripcion'];

                $uploaddir = '../img_xml/';
                    
                $nameimg  = basename($_FILES['changeimagen']['name']);
                $extension = pathinfo($nameimg, PATHINFO_EXTENSION);
                $newnameimg       = $row['idColeccion'].'_'.$idDocument.'.'.$extension;
                
                $namexml  = basename($_FILES['changetranscripcion']['name']);
                $extension = pathinfo($namexml, PATHINFO_EXTENSION);
                $newnamexml       = $row['idColeccion'].'_'.$idDocument.'.'.$extension;
                
                $uploadimg = $uploaddir . $newnameimg;
                $uploadxml = $uploaddir . $newnamexml;
                move_uploaded_file($_FILES['changeimagen']['tmp_name'], $uploadimg);
                move_uploaded_file($_FILES['changetranscripcion']['tmp_name'], $uploadxml);
            
                $update = documentService::updateFilesById($idDocument, utf8_decode($uploadimg), utf8_decode($uploadxml));
                if($update!=FALSE){
                    //Delete old files
                    try{
                    unlink($imagen);
                    unlink($transcripcion);
                    }
                    catch (Exception $e) {
                        echo 'Excepción eliminar documentos: ',  $e->getMessage(), "\n";
                    }
                    header("Location: ../view/documentAdmin.php");
                }
                
            }
        }
    }
    
    /**
     * Function to access a document by a student.
     *
     * Get the document information and the path to the image of the document.
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version  1.0
     *  @access   public
     *  @return void
     */
    function accessDoc(){
        $idDocument = $_POST['idDocument'];
        
        $document =documentService::getById($idDocument);
        if($document!=FALSE){
                $imagen = utf8_encode($document->getImageDocument());
                $nombre = utf8_encode($document->getNameDocument());
                $descripcion = utf8_encode($document->getDescriptionDocument());
                $fecha = utf8_encode($document->getDateDocument());
                $tipoEscritura = utf8_encode($document->getTypeWritingDocument());
                
                $res= 1;
        }else{
            $res= 0;
        }
        
        $data = array(
            "result"=>$res,
            "image"=>$imagen,
            "nombre"=> $nombre,
            "descripcion"=> $descripcion,
            "fecha"=> $fecha,
            "tipoEscritura" => $tipoEscritura
        );
        $outputdata = json_encode($data);
        print($outputdata);  
    }
?> 