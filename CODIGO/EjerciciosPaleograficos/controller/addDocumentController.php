<?php
    session_start();
    include('../model/acceso_db.php');
    
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
    }
    
    function addNewDocs(){
        $coleccion = $_POST['coleccion'];
        $idColeccion = $_POST['idColeccion'];
        
        if($_FILES['imagen']['size'] > 0 && $_FILES['transcripcion']['size'] > 0)
        {
            $name = mysqli_real_escape_string($GLOBALS['link'],$_POST['name']);
            $description = mysqli_real_escape_string($GLOBALS['link'],$_POST['description']);
            $type = mysqli_real_escape_string($GLOBALS['link'],$_POST['type']);
            $date = mysqli_real_escape_string($GLOBALS['link'],$_POST['date']); 
        
            //Insertamos en la BD
            $reg = mysqli_query($GLOBALS['link'],"INSERT INTO documento (documento.nombre, documento.descripcion, documento.fecha,documento.tipoEscritura) VALUES ('".utf8_decode($name)."','".utf8_decode($description)."','".utf8_decode($date)."','".utf8_decode($type)."')");
            if($reg) {
                $result= mysqli_query($GLOBALS['link'],"SELECT documento.idDocumento FROM documento WHERE documento.nombre='".utf8_decode($name)."'");
                $idDocument=mysqli_fetch_assoc($result);
                $idDocument = $idDocument['idDocumento'];
                
                $uploaddir = '../img_xml/';
                
                $nameimg  = basename($_FILES['imagen']['name']);
                $extension = pathinfo($nameimg, PATHINFO_EXTENSION);
                $newnameimg       = $idColeccion.'_'.$idDocument.'.'.$extension;
                
                $namexml  = basename($_FILES['transcripcion']['name']);
                $extension = pathinfo($namexml, PATHINFO_EXTENSION);
                $newnamexml       = $idColeccion.'_'.$idDocument.'.'.$extension;
                
                $uploadimg = $uploaddir . $newnameimg;
                $uploadxml = $uploaddir . $newnamexml;
                move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadimg);
                move_uploaded_file($_FILES['transcripcion']['tmp_name'], $uploadxml);
                
                $result2 = mysqli_query($GLOBALS['link'],"UPDATE documento SET documento.imagen='".utf8_decode($uploadimg)."', documento.transcripcion='".utf8_decode($uploadxml)."' WHERE documento.idDocumento='".$idDocument."'");
                $reg2 = mysqli_query($GLOBALS['link'],"INSERT INTO coleccion_documento (coleccion_documento.idColeccion, coleccion_documento.idDocumento) VALUES ('".utf8_decode($idColeccion)."','".utf8_decode($idDocument)."')");
                 
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
            $reg = mysqli_query($GLOBALS['link'],"INSERT INTO documento (documento.nombre, documento.descripcion, documento.fecha,documento.tipoEscritura) VALUES ('".utf8_decode($name)."','".utf8_decode($description)."','".utf8_decode($date)."','".utf8_decode($type)."')");
            if($reg) {
                $result= mysqli_query($GLOBALS['link'],"SELECT documento.idDocumento FROM documento WHERE documento.nombre='".utf8_decode($name)."'");
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
                
                $result2 = mysqli_query($GLOBALS['link'],"UPDATE documento SET documento.imagen='".utf8_decode($uploadimg)."', documento.transcripcion='".utf8_decode($uploadxml)."' WHERE documento.idDocumento='".$idDocument."'");
                for($i = 0;$i<count($coleccionesArray);$i++){
                    $reg2 = mysqli_query($GLOBALS['link'],"INSERT INTO coleccion_documento (coleccion_documento.idColeccion, coleccion_documento.idDocumento) VALUES ('".utf8_decode($coleccionesArray[$i])."','".utf8_decode($idDocument)."')");
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

            $result = mysqli_query($GLOBALS['link'],"SELECT documento.imagen,documento.transcripcion FROM documento WHERE documento.idDocumento= '".$idDocument."'");
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
            
                $result2 = mysqli_query($GLOBALS['link'],"UPDATE documento SET documento.imagen='".utf8_decode($uploadimg)."', documento.transcripcion='".utf8_decode($uploadxml)."' WHERE documento.idDocumento='".$idDocument."'");
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


            $result = mysqli_query($GLOBALS['link'],"SELECT documento.imagen,documento.transcripcion,coleccion_documento.idColeccion FROM documento,coleccion_documento WHERE documento.idDocumento= '".$idDocument."' AND coleccion_documento.idDocumento=documento.idDocumento");
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
            
                $result2 = mysqli_query($GLOBALS['link'],"UPDATE documento SET documento.imagen='".utf8_decode($uploadimg)."', documento.transcripcion='".utf8_decode($uploadxml)."' WHERE documento.idDocumento='".$idDocument."'");
                if($result2!=FALSE){
                    //Delete old files
                    unlink($imagen);
                    unlink($transcripcion);
                    header("Location: ../view/documentAdmin.php");
                }
                
            }
        }
    }
?> 