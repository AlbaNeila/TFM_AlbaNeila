<?php
ob_start();
include("../model/persistence/acceso_db.php");
include("../model/Group.php");
/**
* groupService class.
* 
* This class is used to access to the data base when groupController need it.
*
* @package  model/persistence
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
*/
class groupService{
    
    
    //SELECT QUERIES
    static function getByName($groupName){
        $result = mysqli_query($GLOBALS['link'],"SELECT grupo.idGrupo FROM grupo WHERE grupo.nombre= '".$groupName."'");
        if($row = mysqli_fetch_assoc($result)){
            return $row['idGrupo'];
        }else{
            return false;
        }
    }
    
    static function checkNameNotRepeat($nameGroup,$idGroup){
        $result = mysqli_query($GLOBALS['link'],"SELECT grupo.nombre FROM grupo WHERE grupo.nombre= '".$nameGroup."' and grupo.idGrupo<>'".$idGroup."'");
        if($row = mysqli_fetch_assoc($result)){
            return true;
        }else{
            return false;
        }
    }
    
    static function getDescriptionById($idGroup){
        $result = mysqli_query($GLOBALS['link'],"SELECT grupo.descripcion FROM grupo WHERE grupo.idGrupo='".$idGroup."'");
        if($row = mysqli_fetch_assoc($result)){
            return $row['descripcion'];
        }else{
            return null;
        }
    }
    
    //INSERT QUERIES
    static function insertGroup($grupo,$descripcion,$usuarioCreador){
        $result = mysqli_query($GLOBALS['link'],"INSERT INTO grupo (grupo.nombre, grupo.descripcion, grupo.idUsuarioCreador) VALUES ('".$grupo."','".$descripcion."','".$usuarioCreador."')");
        if($result){
            $result2 = mysqli_query($GLOBALS['link'],"SELECT grupo.idGrupo FROM grupo WHERE grupo.nombre='".$grupo."'");
            if($row = mysqli_fetch_assoc($result2)){
                return $row['idGrupo'];
            }else{
                return null;
            }
        }else{
            return null;
        }
    }
    
    //DELETE QUERIES
    static function deleteById($idGroup){
        $result = mysqli_query($GLOBALS['link'],"DELETE FROM grupo WHERE grupo.idGrupo= '".$idGroup."'");
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    //UPDATE QUERIES
    static function updateById($groupName,$description,$idGroup){
        $result = mysqli_query($GLOBALS['link'],"UPDATE grupo SET grupo.nombre='".$groupName."', grupo.descripcion='".$description."' WHERE grupo.idGrupo='".$idGroup."'");
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    
    /*** USUARIO_GRUPO ***/
    
    //INSERT QUERIES
    static function insertUserGroupRequest($idUser,$idGroup){
        $result = mysqli_query($GLOBALS['link'],"INSERT INTO usuario_grupo (usuario_grupo.idUsuario,usuario_grupo.idGrupo,usuario_grupo.solicitud) VALUES ('".$idUser."', '".$idGroup."', '1')");
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    static function insertUsuarioGrupo($idUser,$idGroup){
        $result = mysqli_query($GLOBALS['link'],"INSERT INTO usuario_grupo (usuario_grupo.idGrupo, usuario_grupo.idUsuario) VALUES ('".$idGroup."','".$idUser."')");
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    //UPDATE QUERIES
    static function updateUserGroupAccess($idGroup,$idUser){
        $result = mysqli_query($GLOBALS['link'],"UPDATE usuario_grupo SET usuario_grupo.solicitud='0' WHERE usuario_grupo.idGrupo='".$idGroup."' AND usuario_grupo.idUsuario='".$idUser."'");
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    //DELETE QUERIES
    static function deleteUserGroupByIds($idGroup,$idUser){
        $result = mysqli_query($GLOBALS['link'],"DELETE FROM usuario_grupo WHERE usuario_grupo.idGrupo='".$idGroup."' AND usuario_grupo.idUsuario='".$idUser."'");
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    //SELECT QUERIES
    static function getUsuarioGrupoByIds($idGroup,$idUser){
        return mysqli_query($GLOBALS['link'],"SELECT usuario_grupo.idGrupo FROM usuario_grupo WHERE usuario_grupo.idGrupo= '".$idGroup."' and usuario_grupo.idUsuario='".$idUser."'");
    }
}
?>