<?php
include("../model/persistence/acceso_db.php");
include("../model/Collection.php");
class collectionService{
    
    
    //SELECT QUERIES
    static function getByName($colName){
        $result = mysqli_query($GLOBALS['link'],"SELECT coleccion.idColeccion FROM coleccion WHERE coleccion.nombre= '".$colName."'");
        if($row = mysqli_fetch_assoc($result)){
            return true;
        }else{
            return false;
        }
    }
    
    static function checkNameNotRepeat($colName,$idCol){
        $result = mysqli_query($GLOBALS['link'],"SELECT coleccion.idColeccion FROM coleccion WHERE coleccion.nombre= '".$colName."' and coleccion.idColeccion<>'".$idCol."'");
        if($row = mysqli_fetch_assoc($result)){
            return true;
        }else{
            return false;
        }
    }
    
    
    //INSERT QUERIES
    static function insertCollection($collection,$description){
        $result = mysqli_query($GLOBALS['link'],"INSERT INTO coleccion (coleccion.nombre, coleccion.descripcion) VALUES ('".$collection."','".$description."')");
        if($result){
            $result2 = mysqli_query($GLOBALS['link'],"SELECT coleccion.idColeccion FROM grupo WHERE coleccion.nombre='".$collection."'");
            if($row = mysqli_fetch_assoc($result2)){
                return $row['idColeccion'];
            }else{
                return null;
            }
        }else{
            return null;
        }
    }
    
    //DELETE QUERIES
    static function deleteById($idCol){
        $result = mysqli_query($GLOBALS['link'],"DELETE FROM coleccion WHERE coleccion.idColeccion= '".$idCol."'");
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    //UPDATE QUERIES
    static function updateById($colName,$description,$idCol){
        $result = mysqli_query($GLOBALS['link'],"UPDATE coleccion SET coleccion.nombre='".$colName."', coleccion.descripcion='".$description."' WHERE coleccion.idColeccion='".$idCol."'");
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    
    /*** GRUPO_COLECCION ***/
    
    //INSERT QUERIES
    static function insertGroupCollection($idGroup,$idCol){
        $return = mysqli_query($GLOBALS['link'],"INSERT INTO grupo_coleccion (grupo_coleccion.idGrupo, grupo_coleccion.idColeccion) VALUES ('".$idGroup."','".$idCol."')");
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    //SELECT QUERIES
    static function getGroupCollectionByIds($idGroup,$idCol){
        $result = mysqli_query($GLOBALS['link'],"SELECT grupo_coleccion.idGrupo FROM grupo_coleccion WHERE grupo_coleccion.idGrupo= '".$idGroup."' and grupo_coleccion.idColeccion='".$idCol."'");
        if($row = mysqli_fetch_assoc($result)){
            return true;
        }else{
            return false;
        }
    }
    
    //DELETE QUERIES
    static function deleteGroupCollectionByIds($idGroup,$idCol){
        $return = mysqli_query($GLOBALS['link'],"DELETE FROM grupo_coleccion WHERE grupo_coleccion.idGrupo= '".$idGroup."' AND grupo_coleccion.idColeccion='".$idCol."'");
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    
}
?>