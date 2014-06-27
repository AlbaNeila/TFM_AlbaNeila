<?php
include("../model/acceso_db.php");
class userService{
    
    
    //SELECT QUERIES
    static function getUserByName($nameUser){
        return mysqli_query($GLOBALS['link'],"SELECT usuario.idUsuario,usuario.email FROM usuario WHERE usuario.usuario='".$nameUser."'");
    }
    
    static function checkNameNotRepeat($nameUser,$idUser){
        return mysqli_query($GLOBALS['link'],"SELECT usuario.nombre FROM usuario WHERE usuario.usuario= '".$nameUser."' and usuario.idUsuario<>'".$idUser."'");
    }
    
    //INSERT QUERIES
    static function insertUser($dni,$password,$name,$surnames,$email){
        return mysqli_query($GLOBALS['link'],"INSERT INTO usuario (usuario.usuario, usuario.password, usuario.nombre, usuario.apellidos, usuario.email, usuario.tipo) VALUES ('".$dni."', '".$password."', '".$name."','".$surnames."','".$email."', 'ALUMNO')");
    }
    
    static function insertTeacher($dni,$password,$name,$surnames,$email){
        return mysqli_query($GLOBALS['link'],"INSERT INTO usuario (usuario.usuario, usuario.password, usuario.nombre, usuario.apellidos, usuario.email, usuario.tipo) VALUES ('".$dni."', '".$password."', '".$name."','".$surnames."','".$email."', 'PROFESOR')");
    }
    
    //DELETE QUERIES
    static function deleteById($idUser){
        return mysqli_query($GLOBALS['link'],"DELETE FROM usuario WHERE usuario.idUsuario= '".$idUser."'");
    }
    
    //UPDATE QUERIES
    static function updateById($dni,$surnames,$email,$name,$password,$idUser){
        return mysqli_query($GLOBALS['link'],"UPDATE usuario SET usuario.nombre='".$dni."', usuario.apellidos='".$surnames."',usuario.email='".$email."' ,usuario.usuario='".$name."' WHERE usuario.idUsuario='".$idUser."'");
    }
    
    static function updatePasswordById($password,$idUser){
        return mysqli_query($GLOBALS['link'],"UPDATE usuario SET usuario.password='".$password."' WHERE usuario.idUsuario='".$idUser."'");
    }

}
?>