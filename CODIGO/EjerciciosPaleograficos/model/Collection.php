<?php

class Collection{
    private $idCollection;
    private $name;
    private $description;
    
    public function Collection($idCollection,$name,$description){
        $this->idCollection = $idCollection;
        $this->name = $name;
        $this->description = $description;
    }
    
    
    public function getIdCollection(){
        return $this->idCollection;
    }
    
    public function getNameCollection(){
        return $this->name;
    }
    
    public function getDescriptionCollection(){
        return $this->description;
    }
    
    public function setNameCollection($name){
        $this->name=$name;
    }
    
    public function setDescriptionCollection($description){
        $this->description=$description;
    }
}
?>