<?php
include("../model/acceso_db.php");
class groupService{
    
    
    //SELECT QUERIES
    static function getByName($groupName){
        return mysqli_query($GLOBALS['link'],"SELECT grupo.idGrupo FROM grupo WHERE grupo.nombre= '".$groupName."'");
    }
    
    static function checkNameNotRepeat($nameGroup,$idGroup){
        return mysqli_query($GLOBALS['link'],"SELECT grupo.nombre FROM grupo WHERE grupo.nombre= '".$nameGroup."' and grupo.idGrupo<>'".$idGroup."'");
    }
    
    static function getDescriptionById($idGroup){
        return mysqli_query($GLOBALS['link'],"SELECT grupo.descripcion FROM grupo WHERE grupo.idGrupo='".$idGroup."'");
    }
    
    //INSERT QUERIES
    static function insertGroup($grupo,$descripcion,$usuarioCreador){
        return mysqli_query($GLOBALS['link'],"INSERT INTO grupo (grupo.nombre, grupo.descripcion, grupo.idUsuarioCreador) VALUES ('".$grupo."','".$descripcion."','".$usuarioCreador."')");
    }
    
    //DELETE QUERIES
    static function deleteById($idGroup){
        return mysqli_query($GLOBALS['link'],"DELETE FROM grupo WHERE grupo.idGrupo= '".$idGroup."'");
    }
    
    //UPDATE QUERIES
    static function updateById($groupName,$description,$idGroup){
        return mysqli_query($GLOBALS['link'],"UPDATE grupo SET grupo.nombre='".$groupName."', grupo.descripcion='".$description."' WHERE grupo.idGrupo='".$idGroup."'");
    }
    
    
    /*** USUARIO_GRUPO ***/
    
    //INSERT QUERIES
    static function insertUsuarioGrupoSolicitud($idUser,$idGroup){
        return mysqli_query($GLOBALS['link'],"INSERT INTO usuario_grupo (usuario_grupo.idUsuario,usuario_grupo.idGrupo,usuario_grupo.solicitud) VALUES ('".$idUser."', '".$idGroup."', '1')");
    }
    
    static function insertUsuarioGrupo($idUser,$idGroup){
        return mysqli_query($GLOBALS['link'],"INSERT INTO usuario_grupo (usuario_grupo.idGrupo, usuario_grupo.idUsuario) VALUES ('".$idGroup."','".$idUser."')");
    }
    
    //UPDATE QUERIES
    static function updateUsuarioGrupoAccess($idGroup,$idUser){
        return mysqli_query($GLOBALS['link'],"UPDATE usuario_grupo SET usuario_grupo.solicitud='0' WHERE usuario_grupo.idGrupo='".$idGroup."' AND usuario_grupo.idUsuario='".$idUser."'");
    }
    
    //DELETE QUERIES
    static function deleteUsuarioGrupoByIds($idGroup,$idUser){
        return mysqli_query($GLOBALS['link'],"DELETE FROM usuario_grupo WHERE usuario_grupo.idGrupo='".$idGroup."' AND usuario_grupo.idUsuario='".$idUser."'");
    }
    
    //SELECT QUERIES
    static function getUsuarioGrupoByIds($idGroup,$idUser){
        return mysqli_query($GLOBALS['link'],"SELECT usuario_grupo.idGrupo FROM usuario_grupo WHERE usuario_grupo.idGrupo= '".$idGroup."' and usuario_grupo.idUsuario='".$idUser."'");
    }
}
?>