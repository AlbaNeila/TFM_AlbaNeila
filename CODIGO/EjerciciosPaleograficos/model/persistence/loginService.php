<?php
include("../model/persistence/acceso_db.php");
class loginService{
    
    
    //SELECT QUERIES
    static function checkLogin($nameUser,$passwordUser){
        return mysqli_query($GLOBALS['link'],"SELECT usuario.idUsuario, usuario.usuario, usuario.tipo FROM usuario WHERE usuario.usuario= '".$nameUser."'   AND usuario.password= '".$passwordUser."' ");
    }
    
    //INSERT QUERIES

    
    //DELETE QUERIES

    
    //UPDATE QUERIES


}
?>