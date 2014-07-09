<?php

class Document{
    private $idDocument;
    private $name;
    private $description;
    private $date;
    private $typeWriting;
    private $image;
    private $transcription;
    
    public function Document($idDocument,$name,$description,$date,$typeWriting,$image,$transcription){
        $this->idDocument = $idDocument;
        $this->name = $name;
        $this->description = $description;
        $this->date = $date;
        $this->typeWriting = $typeWriting;
        $this->image = $image;
        $this->transcription = $transcription;
    }
    
    
    public function getIdDocument(){
        return $this->idDocument;
    }
    
    public function getNameDocument(){
        return $this->name;
    }
    
    public function getDescriptionDocument(){
        return $this->description;
    }
    
    public function getDateDocument(){
        return $this->date;
    }
    
    public function getTypeWritingDocument(){
        return $this->typeWriting;
    }
    
    public function getImageDocument(){
        return $this->image;
    }
    public function getTranscriptionDocument(){
        return $this->transcription;
    }
    
    public function setIdDocument($idDocument){
        $this->idDocument=$idDocument;
    }
    
    public function setNameDocument($nameDocument){
        $this->name=$nameDocument;
    }
    
    public function setDescriptionDocument($descriptionDocument){
        $this->description=$descriptionDocument;
    }
    
    public function setDateDocument($dateDocument){
        $this->date=$dateDocument;
    }
    
    public function setTypeWritingDocument($typeWritingDocument){
        $this->typeWriting=$typeWritingDocument;
    }
    
    public function setImageDocument($imageDocument){
        $this->image=$imageDocument;
    }
    public function setTranscriptionDocument($transcriptionDocument){
        $this->transcription=$transcriptionDocument;
    }
}
?>