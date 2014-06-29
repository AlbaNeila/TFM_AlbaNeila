<?php

class User{
    private $idUser;
    private $user;
    private $password;
    private $name;
    private $surnames;
    private $email;
    private $type;
    
    public function User($idUser,$user,$password,$name,$surnames,$email,$type){
        $this->idUser = $idUser;
        $this->user = $user;
        $this->password = $password;
        $this->name = $name;
        $this->surnames = $surnames;
        $this->email = $email;
        $this->type = $type;
    }
    
    
    public function getIdUser(){
        return $this->idUser;
    }
    
    public function getUser(){
        return $this->user;
    }
    
    public function getPassword(){
        return $this->password;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getSurnames(){
        return $this->surnames;
    }
    
    public function getEmail(){
        return $this->email;
    }
    
    public function getType(){
        return $this->type;
    }
    
    public function setIdUser($idUser){
        $this->idUser=$idUser;
    }
    
    public function setUser($user){
        $this->user=$user;
    }
    
    public function setPassword($password){
        $this->password=$password;
    }
    
    public function setName($name){
        $this->name=$name;
    }
    
    public function setSurnames($surnames){
        $this->surnames=$surnames;
    }
    
    public function setEmail($email){
        $this->email=$email;
    }
    
    public function setType($type){
        $this->type=$type;
    }
}
?>