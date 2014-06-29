<?php

class Group{
    private $idGroup;
    private $name;
    private $description;
    
    public function Group($idGroup,$name,$description){
        $this->idGroup = $idGroup;
        $this->name = $name;
        $this->description = $description;
    }
    
    
    public function getIdGroup(){
        return $this->idGroup;
    }
    
    public function getNameGroup(){
        return $this->name;
    }
    
    public function getDescriptionGroup(){
        return $this->description;
    }
    
    public function setNameGroup($name){
        $this->name=$name;
    }
    
    public function setDescriptionGroup($description){
        $this->description=$description;
    }
}
?>