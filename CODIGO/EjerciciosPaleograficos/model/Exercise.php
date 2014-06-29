<?php

class Exercise{
    private $idExercise;
    private $name;
    private $typeTarget;
    private $valueTarget;
    private $correctionMode;
    private $idDocument;
    private $idDifficulty;
    
    public function Exercise($idExercise,$name,$typeTarget,$valueTarget,$correctionMode,$idDocument,$idDifficulty){
        $this->idExercise = $idExercise;
        $this->name = $name;
        $this->typeTarget = $typeTarget;
        $this->valueTarget = $valueTarget;
        $this->correctionMode = $correctionMode;
        $this->idDocument = $idDocument;
        $this->idDifficulty = $idDifficulty;
    }
    
    
    public function getIdExercise(){
        return $this->idExercise;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function getTypeTarget(){
        return $this->typeTarget;
    }
    
    public function getValueTarget(){
        return $this->valueTarget;
    }
    
    public function getCorrectionMode(){
        return $this->correctionMode;
    }
    
    public function getIdDocument(){
        return $this->idDocument;
    }
    public function getIdExercise(){
        return $this->idExercise;
    }
    
    public function setIdExercise($idExercise){
        $this->idExercise=$idExercise;
    }
    
    public function setName($name){
        $this->name=$name;
    }
    
    public function setTypeTarget($typeTarget){
        $this->typeTarget=$typeTarget;
    }
    
    public function setValueTarget($valueTarget){
        $this->valueTarget=$valueTarget;
    }
    
    public function setCorrectionMode($correctionMode){
        $this->correctionMode=$correctionMode;
    }
    
    public function setIdDocument($idDocument){
        $this->idDocument=$correctionMode;
    }
    public function setIdDifficulty($idDifficulty){
        $this->transcription=$transcriptionDocument;
    }
}
?>