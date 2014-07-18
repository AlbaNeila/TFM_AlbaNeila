<?php
include("../model/persistence/acceso_db.php");
include("../model/Document.php");
include("../model/Collection.php");
/**
* documentService class.
* 
* This class is used to access to the data base when documentController need it.
*
* @package  model/persistence
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
*/
class documentService{
    
    
    //SELECT QUERIES
    static function getById($idDoc){
        $result = mysqli_query($GLOBALS['link'],"SELECT documento.imagen,documento.transcripcion,documento.nombre,documento.descripcion,documento.fecha,documento.tipoEscritura FROM documento WHERE documento.idDocumento= '".$idDoc."'");
        if($row = mysqli_fetch_assoc($result)){
            $document = new Document($idDoc,$row['nombre'],$row['descripcion'],$row['fecha'],$row['tipoEscritura'],$row['imagen'],$row['transcripcion']);
            return $document;
        }else{
            return null;
        }
    }
    
    static function getByName($document){
        $result = mysqli_query($GLOBALS['link'],"SELECT documento.idDocumento FROM documento WHERE documento.nombre= '".$document."'");
        if($row = mysqli_fetch_assoc($result)){
            return true;
        }else{
            return false;
        }
    }
    
    static function checkNameNotRepeat($nombre,$idDoc){
        $result = mysqli_query($GLOBALS['link'],"SELECT documento.nombre FROM documento WHERE documento.nombre= '".$nombre."' and documento.idDocumento<>'".$idDoc."'");
        if($row = mysqli_fetch_assoc($result)){
            return true;
        }else{
            return false;
        }
    }
    
    static function getFilesById($idDoc){
        return mysqli_query($GLOBALS['link'],"SELECT documento.imagen,documento.transcripcion FROM documento WHERE documento.idDocumento= '".$idDoc."'");
    }
    
    //INSERT QUERIES
    static function insertDocument($name,$description,$date,$type){
        $result = mysqli_query($GLOBALS['link'],"INSERT INTO documento (documento.nombre, documento.descripcion, documento.fecha,documento.tipoEscritura) VALUES ('".$name."','".$description."','".$date."','".$type."')");
        if($result){
            $result2 = mysqli_query($GLOBALS['link'],"SELECT documento.idDocumento FROM documento WHERE documento.nombre= '".$name."'");
            if($row = mysqli_fetch_assoc($result2)){
                return $row['idDocumento'];
            }
        }else{
            return null;
        }
    }
    
    //DELETE QUERIES
    static function deleteById($idDoc){
        $result = mysqli_query($GLOBALS['link'],"DELETE FROM documento WHERE documento.idDocumento= '".$idDoc."'");
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    //UPDATE QUERIES
    static function updateById($idDocument,$nombre,$descripcion,$fecha,$tipoEscritura){
        $result = mysqli_query($GLOBALS['link'],"UPDATE documento SET documento.nombre='".$nombre."', documento.descripcion='".$descripcion."',documento.fecha='".$fecha."' ,documento.tipoEscritura='".$tipoEscritura."' WHERE documento.idDocumento='".$idDocument."'");
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    static function updateFilesById($idDocument,$uploadimg,$uploadxml){
        $result = mysqli_query($GLOBALS['link'],"UPDATE documento SET documento.imagen='".$uploadimg."', documento.transcripcion='".$uploadxml."' WHERE documento.idDocumento='".$idDocument."'");
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    
    
    /*** COLECCION_DOCUMENTO ***/
    
    //INSERT QUERIES
    static function insertColeccionDocumento($idCol,$idDoc){
        return mysqli_query($GLOBALS['link'],"INSERT INTO coleccion_documento (coleccion_documento.idColeccion, coleccion_documento.idDocumento) VALUES ('".$idCol."','".$idDoc."')");
    }
    
    //SELECT QUERIES
    static function getFilesCollectionById($idDoc){
        return mysqli_query($GLOBALS['link'],"SELECT documento.imagen,documento.transcripcion,coleccion_documento.idColeccion FROM documento,coleccion_documento WHERE documento.idDocumento= '".$idDoc."' AND coleccion_documento.idDocumento=documento.idDocumento");
    }
    
    static function getColeccionDocumentoByIdDoc($idDocument){
        return mysqli_query($GLOBALS['link'],"SELECT coleccion_documento.idColeccion FROM coleccion_documento WHERE coleccion_documento.idDocumento= '".$idDocument."'");
    }
    
    static function getColleccionDocumentoByIds($idDoc,$idCol){
        return mysqli_query($GLOBALS['link'],"SELECT coleccion_documento.idColeccion FROM coleccion_documento WHERE coleccion_documento.idDocumento= '".$idDoc."' AND coleccion_documento.idColeccion='".$idCol."'");
    }
    
    //DELETE QUERIES
    static function deleteColleccionDocumentoByIds($idCol,$idDoc){
        $result = mysqli_query($GLOBALS['link'],"DELETE FROM coleccion_documento WHERE coleccion_documento.idColeccion='".$idCol."' AND coleccion_documento.idDocumento='".$idDoc."'");
        if($result){
            return true;
        }else{
            return false;
        }
    }
}
?>