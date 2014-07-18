<?php
/**
* Group class.
*
* @package  model
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
*/
class Group{
    /**
     * Id of the group.
     */
    private $idGroup;
    
    /**
     * Name of the group.
     */
    private $name;
    
    /**
     * Description of the group.
     */
    private $description;
    
    /**
     * Constructor of the Group class.
     */
    public function Group($idGroup,$name,$description){
        $this->idGroup = $idGroup;
        $this->name = $name;
        $this->description = $description;
    }
    
    /**
     * Get the Id of the Group.
     */
    public function getIdGroup(){
        return $this->idGroup;
    }
    
    /**
     * Get the name of the Group.
     */
    public function getNameGroup(){
        return $this->name;
    }
    
    /**
     * Get the description of the Group.
     */
    public function getDescriptionGroup(){
        return $this->description;
    }
    
    /**
     * Set the name of the Group.
     */
    public function setNameGroup($name){
        $this->name=$name;
    }
    
     /**
     * Set the description of the Group.
     */
    public function setDescriptionGroup($description){
        $this->description=$description;
    }
}
?>