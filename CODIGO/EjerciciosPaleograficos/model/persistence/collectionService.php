<?php
include("../model/grid_acceso_db.php");
class collectionService{
    
    
    //SELECT QUERIES
    static function getByName($colName){
        return mysqli_query($GLOBALS['link'],"SELECT coleccion.idColeccion FROM coleccion WHERE coleccion.nombre= '".$colName."'");
    }
    
    static function checkNameNotRepeat($colName,$idCol){
        return mysqli_query($GLOBALS['link'],"SELECT coleccion.idColeccion FROM coleccion WHERE coleccion.nombre= '".$colName."' and coleccion.idColeccion<>'".$idCol."'");
    }
    
    
    //INSERT QUERIES
    static function insertCollection($collection,$description){
        return mysqli_query($GLOBALS['link'],"INSERT INTO coleccion (coleccion.nombre, coleccion.descripcion) VALUES ('".$collection."','".$description."')");
    }
    
    //DELETE QUERIES
    static function deleteById($idCol){
        return mysqli_query($GLOBALS['link'],"DELETE FROM coleccion WHERE coleccion.idColeccion= '".$idCol."'");
    }
    
    //UPDATE QUERIES
    static function updateById($colName,$description,$idCol){
        return mysqli_query($GLOBALS['link'],"UPDATE coleccion SET coleccion.nombre='".$colName."', coleccion.descripcion='".$description."' WHERE coleccion.idColeccion='".$idCol."'");
    }
    
    
    /*** GRUPO_COLECCION ***/
    
    //INSERT QUERIES
    static function insertGroupCollection($idGroup,$idCol){
        return mysqli_query($GLOBALS['link'],"INSERT INTO grupo_coleccion (grupo_coleccion.idGrupo, grupo_coleccion.idColeccion) VALUES ('".$idGroup."','".$idCol."')");
    }
    
    //SELECT QUERIES
    static function getGroupCollectionByIds($idGroup,$idCol){
        return mysqli_query($GLOBALS['link'],"SELECT grupo_coleccion.idGrupo FROM grupo_coleccion WHERE grupo_coleccion.idGrupo= '".$idGroup."' and grupo_coleccion.idColeccion='".$idCol."'");
    }
    
    //DELETE QUERIES
    static function deleteGroupCollectionByIds($idGroup,$idCol){
        return mysqli_query($GLOBALS['link'],"DELETE FROM grupo_coleccion WHERE grupo_coleccion.idGrupo= '".$idGroup."' AND grupo_coleccion.idColeccion='".$idCol."'");
    }
    
    
}
?>