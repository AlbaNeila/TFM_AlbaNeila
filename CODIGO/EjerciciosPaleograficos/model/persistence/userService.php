<?php
include("../model/persistence/acceso_db.php");
include("../model/User.php");
/**
* userService class.
* 
* This class is used to access to the data base when userController need it.
*
* @package  model/persistence
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
*/
class userService{

    //SELECT QUERIES
    /**
     * Function to get the idUsuario and emal from an user by the ID number. 
     *
     * @param string $nameUser ID number of a user.
     * @return mysqli_result idUsuario and email of the user.
     */
    static function getUserByName($nameUser){
        return mysqli_query($GLOBALS['link'],"SELECT usuario.idUsuario,usuario.email FROM usuario WHERE usuario.usuario='".$nameUser."'");
    }
    
    /**
     * Function to get the user fields by the idUsuario of the user. 
     *
     * @param int $idUser id of a user.
     * @return $user the user with the idUsario or null if not found any user with the idUsuario.
     */
    static function getUserById($idUser){
        $result = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario WHERE usuario.idUsuario='".$idUser."'");
        if($row = mysqli_fetch_assoc($result)){
            $user = new User($idUser,$row['usuario'],$row['password'],$row['nombre'],$row['apellidos'],$row['email'],$row['tipo']);
            return $user;
        }else{
            return null;
        }
    }
    
    /**
     * Function to check if exists another user with the same ID number.
     * 
     * Return empty mysqli_result if the ID number is not repeat and return a row if also exist the ID number. 
     * 
     * @param string $nameUser ID number of a user
     * @param int $idUser id of a user.
     * @return mysqli_result ID numberl of the user if is repeated or an empty mysli_result if is not repeat.
     */
    static function checkNameNotRepeat($nameUser,$idUser){
        return mysqli_query($GLOBALS['link'],"SELECT usuario.nombre FROM usuario WHERE usuario.usuario= '".$nameUser."' and usuario.idUsuario<>'".$idUser."'");
    }
    
    //INSERT QUERIES
    /**
     * Function to insert a new student.
     *
     * @param string $dni ID number of a user.
     * @param string $password password of a user.
     * @param string $name name of a user.
     * @param string $surnames surnames of a user.
     * @param string $email email of a user.
     * @return true if the student is inserted ok or false if the student can't be inserted.
     */
    static function insertUser($dni,$password,$name,$surnames,$email){
        return mysqli_query($GLOBALS['link'],"INSERT INTO usuario (usuario.usuario, usuario.password, usuario.nombre, usuario.apellidos, usuario.email, usuario.tipo) VALUES ('".$dni."', '".$password."', '".$name."','".$surnames."','".$email."', 'ALUMNO')");
    }
    
    /**
     * Function to insert a new lecturer.
     *
     * @param string $dni ID number of a user.
     * @param string $password password of a user.
     * @param string $name name of a user.
     * @param string $surnames surnames of a user.
     * @param string $email email of a user.
     * @return true if the lecturer is inserted ok or false if the lecturer can't be inserted.
     */
    static function insertTeacher($dni,$password,$name,$surnames,$email){
        return mysqli_query($GLOBALS['link'],"INSERT INTO usuario (usuario.usuario, usuario.password, usuario.nombre, usuario.apellidos, usuario.email, usuario.tipo) VALUES ('".$dni."', '".$password."', '".$name."','".$surnames."','".$email."', 'PROFESOR')");
    }
    
    //DELETE QUERIES
    /**
     * Function to remove a user by the ID number.
     *
     * @param int $idUser id of a user.
     * @return true if the user is removed ok or false if the user can't be removed.
     */
    static function deleteById($idUser){
        return mysqli_query($GLOBALS['link'],"DELETE FROM usuario WHERE usuario.idUsuario= '".$idUser."'");
    }
    
    //UPDATE QUERIES
    /**
     * Function to update a user by the ID number.
     *
     * @param string $dni ID number of a user.
     * @param string $surnames surnames of a user.
     * @param string $email email of a user.
     * @param string $name name of a user.
     * @param int $idUser id of a user.
     * @return true if the user is updated ok or false if the user can't be updated.
     */
    static function updateById($dni,$surnames,$email,$name,$idUser){
        return mysqli_query($GLOBALS['link'],"UPDATE usuario SET usuario.nombre='".$dni."', usuario.apellidos='".$surnames."',usuario.email='".$email."' ,usuario.usuario='".$name."' WHERE usuario.idUsuario='".$idUser."'");
    }
    
    /**
     * Function to update the user password by the ID number of the user.
     *
     * @param string $password password of a user.
     * @param int $idUser id of a user.
     * @return true if the password is updated ok or false if the password can't be updated.
     */
    static function updatePasswordById($password,$idUser){
        return mysqli_query($GLOBALS['link'],"UPDATE usuario SET usuario.password='".$password."' WHERE usuario.idUsuario='".$idUser."'");
    }

}
?>