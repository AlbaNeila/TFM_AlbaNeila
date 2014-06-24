<?php
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
    
    function addNewDocs(){
        $coleccion = $_POST['coleccion'];
        $idColeccion = $_POST['idColeccion'];
        
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
            $reg = documentService::insertDocument(utf8_decode($name), utf8_decode($description), utf8_decode($date), utf8_decode($type));
            if($reg) {
                $result= documentService::getByName(utf8_decode($name));
                $idDocument=mysqli_fetch_assoc($result);
                $idDocument = $idDocument['idDocumento'];
                
                $uploaddir = '../img_xml/';
                
                $nameimg  = basename($_FILES['imagen']['name']);
                $extension = pathinfo($nameimg, PATHINFO_EXTENSION);
                $newnameimg       = $coleccionesArray[0].'_'.$idDocument.'.'.$extension;
                
                $namexml  = basename($_FILES['transcripcion']['name']);
                $extension = pathinfo($namexml, PATHINFO_EXTENSION);
                $newnamexml       = $coleccionesArray[0].'_'.$idDocument.'.'.$extension;
                
                $uploadimg = $uploaddir . $newnameimg;
                $uploadxml = $uploaddir . $newnamexml;
                move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadimg);
                move_uploaded_file($_FILES['transcripcion']['tmp_name'], $uploadxml);
                
                $result2 = documentService::updateFilesById($idDocument, utf8_decode($uploadimg), utf8_decode($uploadxml));
                for($i = 0;$i<count($coleccionesArray);$i++){
                    $reg2 = documentService::insertColeccionDocumento($coleccionesArray[$i], $idDoc);
                }                
            }
        }
        header("Location: ../view/documentTeacher.php?coleccion=$coleccion+&idColeccion=$idColeccion");
    }

    function addNewDocsAdmin(){
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
            $reg = documentService::insertDocument(utf8_decode($name), utf8_decode($description), utf8_decode($date), utf8_decode($type));
            if($reg) {
                $result= documentService::getByName(utf8_decode($name));
                $idDocument=mysqli_fetch_assoc($result);
                $idDocument = $idDocument['idDocumento'];
                
                $uploaddir = '../img_xml/';
                
                $nameimg  = basename($_FILES['imagen']['name']);
                $extension = pathinfo($nameimg, PATHINFO_EXTENSION);
                $newnameimg       = $coleccionesArray[0].'_'.$idDocument.'.'.$extension;
                
                $namexml  = basename($_FILES['transcripcion']['name']);
                $extension = pathinfo($namexml, PATHINFO_EXTENSION);
                $newnamexml       = $coleccionesArray[0].'_'.$idDocument.'.'.$extension;
                
                $uploadimg = $uploaddir . $newnameimg;
                $uploadxml = $uploaddir . $newnamexml;
                move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadimg);
                move_uploaded_file($_FILES['transcripcion']['tmp_name'], $uploadxml);
                
                $result2 = documentService::updateFilesById($idDocument, utf8_decode($uploadimg), utf8_decode($uploadxml));
                for($i = 0;$i<count($coleccionesArray);$i++){
                    $reg2 = documentService::insertColeccionDocumento($coleccionesArray[$i], $idDocument);
                }                
            }
        }
        header("Location: ../view/documentAdmin.php");
    }
    
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
            
                $result2 = documentService::updateFilesById($idDocument, utf8_decode($uploadimg), utf8_decode($uploadxml));
                if($result2!=FALSE){
                    //Delete old files
                    unlink($imagen);
                    unlink($transcripcion);
                    header("Location: ../view/documentTeacher.php?coleccion=$coleccion+&idColeccion=$idColeccion");
                }
                
            }
        }
    }

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
            
                $result2 = documentService::updateFilesById($idDocument, utf8_decode($uploadimg), utf8_decode($uploadxml));
                if($result2!=FALSE){
                    //Delete old files
                    unlink($imagen);
                    unlink($transcripcion);
                    header("Location: ../view/documentAdmin.php");
                }
                
            }
        }
    }

    function accessDoc(){
        $idDocument = $_POST['idDocument'];
        
        $result =documentService::getById($idDocument);
        if($result!=FALSE){
                $row=mysqli_fetch_assoc($result);
                $imagen = utf8_encode($row['imagen']);
                $nombre = utf8_encode($row['nombre']);
                $descripcion = utf8_encode($row['descripcion']);
                $fecha = utf8_encode($row['fecha']);
                $tipoEscritura = utf8_encode($row['tipoEscritura']);
                
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