<?php
/**
* Class User.
*
* @package  model
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
*/
class User{
    /**
     * Id of the user.
     */
    private $idUser;
    
    /**
     * DNI of the user.
     */
    private $user;
    
    /**
     * Password of the user.
     */
    private $password;
    
    /**
     * Name of the user.
     */
    private $name;
    
    /**
     * Surnames of the user.
     */
    private $surnames;
    
    /**
     * Email of the user.
     */
    private $email;
    
    /**
     * Type of the user.
     */
    private $type;
    
    /**
     * Constructor of the User class.
     */
    public function User($idUser,$user,$password,$name,$surnames,$email,$type){
        $this->idUser = $idUser;
        $this->user = $user;
        $this->password = $password;
        $this->name = $name;
        $this->surnames = $surnames;
        $this->email = $email;
        $this->type = $type;
    }
    
    /**
     * Get the Id of the User.
     */
    public function getIdUser(){
        return $this->idUser;
    }
    
    /**
     * Get the dni of the User.
     */
    public function getUser(){
        return $this->user;
    }
    
    /**
     * Get the passowrd of the User.
     */
    public function getPassword(){
        return $this->password;
    }
    
    /**
     * Get the name of the User.
     */
    public function getName(){
        return $this->name;
    }
    
    /**
     * Get the surnames of the User.
     */
    public function getSurnames(){
        return $this->surnames;
    }
    
    /**
     * Get the email of the User.
     */
    public function getEmail(){
        return $this->email;
    }
    
    /**
     * Get the type of the User.
     */
    public function getType(){
        return $this->type;
    }
    
    /**
     * Set the id of the User.
     */
    public function setIdUser($idUser){
        $this->idUser=$idUser;
    }
    
    /**
     * Set the dni of the User.
     */
    public function setUser($user){
        $this->user=$user;
    }
    
    /**
     * Set the password of the User.
     */
    public function setPassword($password){
        $this->password=$password;
    }
    
    /**
     * Set the name of the User.
     */
    public function setName($name){
        $this->name=$name;
    }
    
    /**
     * Set the surnames of the User.
     */
    public function setSurnames($surnames){
        $this->surnames=$surnames;
    }
    
    /**
     * Set the email of the User.
     */
    public function setEmail($email){
        $this->email=$email;
    }
    
    /**
     * Set the type of the User.
     */
    public function setType($type){
        $this->type=$type;
    }
}
?>