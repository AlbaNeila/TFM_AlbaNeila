<?php
/**
* Collection class.
*
* @package  model
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
*/
class Collection{
    /**
     * Id of the collection.
     */
    private $idCollection;
    
    /**
     * Name of the collection.
     */
    private $name;
    
    /**
     * Description of the collection.
     */
    private $description;
    
    
    /**
     * Constructor of the Collection class.
     */
    public function Collection($idCollection,$name,$description){
        $this->idCollection = $idCollection;
        $this->name = $name;
        $this->description = $description;
    }
    
    
    /**
     * Get the id of the Collection.
     */
    public function getIdCollection(){
        return $this->idCollection;
    }
    
    /**
     * Get the name of the Collection
     */
    public function getNameCollection(){
        return $this->name;
    }
    
    /**
     * Get the description of the Collection
     */
    public function getDescriptionCollection(){
        return $this->description;
    }
    
    /**
     * Set the name of the Collection.
     */
    public function setNameCollection($name){
        $this->name=$name;
    }
    
    /**
     * Set the description of the Collection
     */
    public function setDescriptionCollection($description){
        $this->description=$description;
    }
}
?>