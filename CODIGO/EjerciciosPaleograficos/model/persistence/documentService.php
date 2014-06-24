<?php
include("../model/acceso_db.php");
class documentService{
    
    
    //SELECT QUERIES
    static function getById($idDoc){
        return mysqli_query($GLOBALS['link'],"SELECT documento.imagen,documento.transcripcion,documento.nombre,documento.descripcion,documento.fecha,documento.tipoEscritura FROM documento WHERE documento.idDocumento= '".$idDoc."'");
    }
    
    static function getByName($document){
        return mysqli_query($GLOBALS['link'],"SELECT documento.idDocumento FROM documento WHERE documento.nombre= '".$document."'");
    }
    
    static function checkNameNotRepeat($nombre,$idDoc){
        return mysqli_query($GLOBALS['link'],"SELECT documento.nombre FROM documento WHERE documento.nombre= '".$nombre."' and documento.idDocumento<>'".$idDoc."'");
    }
    
    static function getFilesById($idDoc){
        return mysqli_query($GLOBALS['link'],"SELECT documento.imagen,documento.transcripcion FROM documento WHERE documento.idDocumento= '".$idDoc."'");
    }
    
    //INSERT QUERIES
    static function insertDocument($name,$description,$date,$type){
        return mysqli_query($GLOBALS['link'],"INSERT INTO documento (documento.nombre, documento.descripcion, documento.fecha,documento.tipoEscritura) VALUES ('".$name."','".$description."','".$date."','".$type."')");
    }
    
    //DELETE QUERIES
    static function deleteById($idDoc){
        return mysqli_query($GLOBALS['link'],"DELETE FROM documento WHERE documento.idDocumento= '".$idDoc."'");
    }
    
    //UPDATE QUERIES
    static function updateById($idDocument,$nombre,$descripcion,$fecha,$tipoEscritura){
        return mysqli_query($GLOBALS['link'],"UPDATE documento SET documento.nombre='".$nombre."', documento.descripcion='".$descripcion."',documento.fecha='".$fecha."' ,documento.tipoEscritura='".$tipoEscritura."' WHERE documento.idDocumento='".$idDocument."'");
    }
    
    static function updateFilesById($idDocument,$uploadimg,$uploadxml){
        return mysqli_query($GLOBALS['link'],"UPDATE documento SET documento.imagen='".$uploadimg."', documento.transcripcion='".$uploadxml."' WHERE documento.idDocumento='".$idDocument."'");
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
        return mysqli_query($GLOBALS['link'],"DELETE FROM coleccion_documento WHERE coleccion_documento.idColeccion='".$idCol."' AND coleccion_documento.idDocumento='".$idDoc."'");
    }
}
?>