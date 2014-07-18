<?php
/**
* Document class.
*
* @package  model
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
*/
class Document{
    /**
     * Id of the document.
     */
    private $idDocument;
    
    /**
     * Name of the document.
     */
    private $name;
    
    /**
     * Description of the document.
     */
    private $description;
    
    /**
     * Date of the document.
     */
    private $date;
    
    /**
     * Type of writting of the document.
     */
    private $typeWriting;
    
    /**
     * Image of the document.
     */
    private $image;
    
    /**
     * Transcription of the document.
     */
    private $transcription;
    
    /**
     * Constructor of the Document class.
     */
    public function Document($idDocument,$name,$description,$date,$typeWriting,$image,$transcription){
        $this->idDocument = $idDocument;
        $this->name = $name;
        $this->description = $description;
        $this->date = $date;
        $this->typeWriting = $typeWriting;
        $this->image = $image;
        $this->transcription = $transcription;
    }
    
    /**
     * Get the Id of the Document.
     */
    public function getIdDocument(){
        return $this->idDocument;
    }
    
    /**
     * Get the name of the Document.
     */
    public function getNameDocument(){
        return $this->name;
    }
    
    /**
     * Get the description of the Document.
     */
    public function getDescriptionDocument(){
        return $this->description;
    }
    
    /**
     * Get the date of the Document.
     */
    public function getDateDocument(){
        return $this->date;
    }
    
    /**
     * Get the type of writting of the Document.
     */
    public function getTypeWritingDocument(){
        return $this->typeWriting;
    }
    
    /**
     * Get the image of the Document.
     */
    public function getImageDocument(){
        return $this->image;
    }
    
    /**
     * Get the transcription of the Document.
     */
    public function getTranscriptionDocument(){
        return $this->transcription;
    }
    
    /**
     * Set the Id of the Document.
     */
    public function setIdDocument($idDocument){
        $this->idDocument=$idDocument;
    }
    
    /**
     * Set the name of the Document.
     */
    public function setNameDocument($nameDocument){
        $this->name=$nameDocument;
    }
    
    /**
     * Set the description of the Document.
     */
    public function setDescriptionDocument($descriptionDocument){
        $this->description=$descriptionDocument;
    }
    
    /**
     * Set the date of the Document.
     */
    public function setDateDocument($dateDocument){
        $this->date=$dateDocument;
    }
    
    /**
     * Set the type of writting of the Document.
     */
    public function setTypeWritingDocument($typeWritingDocument){
        $this->typeWriting=$typeWritingDocument;
    }
    
    /**
     * Set the image of the Document.
     */
    public function setImageDocument($imageDocument){
        $this->image=$imageDocument;
    }
    
    /**
     * Set the transcription of the Document.
     */
    public function setTranscriptionDocument($transcriptionDocument){
        $this->transcription=$transcriptionDocument;
    }
}
?>