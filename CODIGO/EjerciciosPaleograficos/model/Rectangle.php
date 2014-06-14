<?php

class Rectangle{
    private $idRectangle;
    private $class;
    private $left;
    private $top;
    private $width;
    private $heigth;
    private $line;
    private $transcription;

    
    public function Rectangle($idRectangle,$class,$left,$top,$width,$heigth,$line){
        $this->idRectangle = $idRectangle;
        $this->class = $class;
        $this->left = $left;
        $this->top = $top;
        $this->width = $width;
        $this->heigth = $heigth;
        $this->line = $line;
    }
    
    
    public function getIdRectangle(){
        return $this->idRectangle;
    }
    
    public function getClassRectangle(){
        return $this->class;
    }
    
    public function getLeftRectangle(){
        return $this->left;
    }
    
    public function getTopRectangle(){
        return $this->top;
    }
    
    public function getWidthRectangle(){
        return $this->width;
    }
    
    public function getHeightRectangle(){
        return $this->heigth;
    }
    
    public function getLineRectangle(){
        return $this->line;
    }
    
    public function getTranscriptionRectangle(){
        return $this->transcription;
    }
    
    public function setTranscriptionRectangle($transcription){
        $this->transcription=$transcription;
    }
}
?>