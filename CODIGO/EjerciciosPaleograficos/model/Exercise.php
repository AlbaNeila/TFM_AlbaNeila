<?php
/**
* Exercise class.
*
* @package  model
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
*/
class Exercise{
    /**
     * Id of the exercise.
     */
    private $idExercise;
    
    /**
     * Name of the exercise.
     */
    private $name;
    
    /**
     * Type of target of the exercise.
     */
    private $typeTarget;
    
    /**
     * Value of target of the exercise.
     */
    private $valueTarget;
    
    /**
     * Correction mode of the exercise.
     */
    private $correctionMode;
    
    /**
     * Id of the document associated to the exercise.
     */
    private $idDocument;
    
    /**
     * Constructor of the Exercise class.
     */
    public function Exercise($idExercise,$name,$typeTarget,$valueTarget,$correctionMode,$idDocument){
        $this->idExercise = $idExercise;
        $this->name = $name;
        $this->typeTarget = $typeTarget;
        $this->valueTarget = $valueTarget;
        $this->correctionMode = $correctionMode;
        $this->idDocument = $idDocument;
    }
    
    /**
     * Get the Id of the Exercise.
     */
    public function getIdExercise(){
        return $this->idExercise;
    }
    
    /**
     * Get the name of the Exercise.
     */
    public function getName(){
        return $this->name;
    }
    
    /**
     * Get the type of target of the Exercise.
     */
    public function getTypeTarget(){
        return $this->typeTarget;
    }
    
    /**
     * Get the value of the target of the Exercise.
     */
    public function getValueTarget(){
        return $this->valueTarget;
    }
    
    /**
     * Get the correction mode of the Exercise.
     */
    public function getCorrectionMode(){
        return $this->correctionMode;
    }
    
    /**
     * Get the Id of the document associated to the Exercise.
     */
    public function getIdDocument(){
        return $this->idDocument;
    }
   
    /**
     * Set the Id of the Exercise.
     */
    public function setIdExercise($idExercise){
        $this->idExercise=$idExercise;
    }
    
    /**
     * Set the name of the Exercise.
     */
    public function setName($name){
        $this->name=$name;
    }
    
    /**
     * Set the type of target of the Exercise.
     */
    public function setTypeTarget($typeTarget){
        $this->typeTarget=$typeTarget;
    }
    
    /**
     * Set the value of the target of the Exercise.
     */
    public function setValueTarget($valueTarget){
        $this->valueTarget=$valueTarget;
    }
    
    /**
     * Set the correction mode of the Exercise.
     */
    public function setCorrectionMode($correctionMode){
        $this->correctionMode=$correctionMode;
    }
    
    /**
     * Set the Id document associated to the Exercise.
     */
    public function setIdDocument($idDocument){
        $this->idDocument=$correctionMode;
    }

}
?>