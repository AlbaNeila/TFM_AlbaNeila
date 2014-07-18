<?php
include("../model/persistence/acceso_db.php");
/**
* loginService class.
* 
* Class to access to the database from loginController.
*
* @package  model/persistence
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
*/
class loginService{
    
    
    //SELECT QUERIES
    /**
     * Function to check that exists an user with the ID and the password received. 
     *
     * @param string $nameUser ID number of a user.
     * @param string $passwordUser password of a user.
     * @return mysqli_result idUsuario, usuario and tipo of the user.
     */
    static function checkLogin($nameUser,$passwordUser){
        return mysqli_query($GLOBALS['link'],"SELECT usuario.idUsuario, usuario.usuario, usuario.tipo FROM usuario WHERE usuario.usuario= '".$nameUser."'   AND usuario.password= '".$passwordUser."' ");
    }
    
    //INSERT QUERIES

    
    //DELETE QUERIES

    
    //UPDATE QUERIES


}
?>