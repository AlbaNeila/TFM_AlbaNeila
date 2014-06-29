<?php

class Difficulty{
    private $idDifficulty;
    private $name;
    private $percentage;
    
    public function Difficulty($idDifficulty,$name,$percentage){
        $this->idDifficulty = $idDifficulty;
        $this->name = $name;
        $this->percentage = $percentage;
    }
    
    
    public function getIdDifficulty(){
        return $this->idDifficulty;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getPercentage(){
        return $this->percentage;
    }
    
    public function setIdDifficulty($idDifficulty){
        $this->idDifficulty=$idDifficulty;
    }
    
    public function setName($name){
        $this->name=$name;
    }
    
    public function setPercentage($percentage){
        $this->percentage=$percentage;
    }
}
?>