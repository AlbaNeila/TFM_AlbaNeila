<?php
/**
* Rectangle class.
*
* @package  model
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
*/
class Rectangle{
    /**
     * Id of the rectangle.
     */
    private $idRectangle;
    
    /**
     * Class of the rectangle.
     */
    private $class;
    
    /**
     * Left position of the rectangle.
     */
    private $left;
    
    /**
     * Top position of the rectangle.
     */
    private $top;
    
    /**
     * Width of the rectangle.
     */
    private $width;
    
    /**
     * Heigth of the rectangle.
     */
    private $heigth;
    
    /**
     * Number of the line of the rectangle.
     */
    private $line;
    
    /**
     * Transcription text of the rectangle.
     */
    private $transcription;

    /**
     * Constructor of the Rectangle class.
     */
    public function Rectangle($idRectangle,$class,$left,$top,$width,$heigth,$line){
        $this->idRectangle = $idRectangle;
        $this->class = $class;
        $this->left = $left;
        $this->top = $top;
        $this->width = $width;
        $this->heigth = $heigth;
        $this->line = $line;
    }
    
    /**
     * Get the Id of the Rectangle.
     */
    public function getIdRectangle(){
        return $this->idRectangle;
    }
    
    /**
     * Get the class of the Rectangle.
     */
    public function getClassRectangle(){
        return $this->class;
    }
    
    /**
     * Get the left position of the Rectangle.
     */
    public function getLeftRectangle(){
        return $this->left;
    }
    
    /**
     * Get the top position of the Rectangle.
     */
    public function getTopRectangle(){
        return $this->top;
    }
    
    /**
     * Get the width of the Rectangle.
     */
    public function getWidthRectangle(){
        return $this->width;
    }
    
    /**
     * Get the height of the Rectangle.
     */
    public function getHeightRectangle(){
        return $this->heigth;
    }
    
    /**
     * Get the number of the line of the Rectangle.
     */
    public function getLineRectangle(){
        return $this->line;
    }
    
    /**
     * Get the transcription text of the Rectangle.
     */
    public function getTranscriptionRectangle(){
        return $this->transcription;
    }
    
    /**
     * Set the transcription text of the Rectangle.
     */
    public function setTranscriptionRectangle($transcription){
        $this->transcription=$transcription;
    }
}
?>