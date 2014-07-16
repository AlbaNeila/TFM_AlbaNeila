<?php
include ("../model/persistence/collectionService.php");
/**
* CollectionServiceTest is a class to test the CollectionService class
*
* @package  test
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
* @backupGlobals disabled
*/
class collectionServiceTest extends PHPUnit_Framework_TestCase {
    
    /**
    * Test that verifies the function getByName returns nothing with a name collection that don't exist
    *
    */
    public function testGetByNameEmpty() {
        $collectionName = "";

        $result = collectionService::getByName($collectionName);
        $this->assertFalse($result);
    }
    
    /**
    * Test that verifies the function getByName returns the correct idCollection of Pública collection
    *
    */
    public function testGetByNamePublic() {
        $collectionName = "Pública";
        $idCollection="";

        $result = collectionService::getByName(utf8_decode($collectionName));

        $this->assertTrue($result);
    }
    
    /**
    * Test that verifies the function checkNameNotRepeat returns that a collection name isn't already exist
    *
    */
    public function testCheckNameNotRepeatTrue(){
        $collectionName = "Nueva colección";
        $idCollection=6;

        $result = collectionService::checkNameNotRepeat($collectionName, $idCollection);
        $this->assertFalse($result);   
    }
    
    /**
    * Test that verifies the function checkNameNotRepeat returns that a collection name is already exist
    *
    */
    public function testCheckNameNotRepeatFalse(){
        $collectionName = "Siglo X";
        $idCollection=6;

        $result = collectionService::checkNameNotRepeat($collectionName, $idCollection);
        $this->assertTrue($result);
    }
    
    /**
    * Test that verifies the function insertCollection insert a collection and the function deleteById delete it 
    *
    */
    public function testInsertDeleteCollection(){
        $collectionName = "Nueva Colección";
        $collectionDescription="Descripción de colección";
        
        $resultBefore  = mysqli_query($GLOBALS['link'],"SELECT * FROM coleccion");
        $rowsBefore = $resultBefore->num_rows;
        
        $idNewCollection = collectionService::insertCollection(utf8_decode($collectionName), utf8_decode($collectionDescription));

        $resultAfter  = mysqli_query($GLOBALS['link'],"SELECT * FROM coleccion");
        $rowsAfter = $resultAfter->num_rows;

        $this->assertGreaterThan($rowsBefore,$rowsAfter);
        
        
        collectionService::deleteById($idNewCollection);
        
        $resultAfterDelete = mysqli_query($GLOBALS['link'],"SELECT * FROM coleccion");
        $rowsAfterDelete = $resultAfterDelete->num_rows;
        
        $this->assertEquals($rowsBefore,$rowsAfterDelete);
        
    }
    
     /**
    * Test that verifies the function updateByid update a collection
    *
    */
    public function testUpdateById(){
        $collectionName = "Siglo XXI";
        $collectionDescription="Documentos pertenecientes al siglo XXI";
        $idCollection=3;
        
        $resultBefore  = mysqli_query($GLOBALS['link'],"SELECT * FROM coleccion");
        $rowsBefore = $resultBefore->num_rows;
        
        $resultUpdate = collectionService::updateById($collectionName, $collectionDescription, $idCollection);
        $result = collectionService::getByName($collectionName);
        
        $this->assertTrue($resultUpdate);
        
    }
    
    /**
    * Test that verifies the function insertGroupCollection, getGroupCollectionByIds  
    *
    */
    public function testInsertGroupCollection(){
       $idGroup=1;
       $idCol=4;
        
        $resultBefore  = mysqli_query($GLOBALS['link'],"SELECT * FROM grupo_coleccion");
        $rowsBefore = $resultBefore->num_rows;
        
        $result = collectionService::insertGroupCollection($idGroup,$idCol);
        $result2 = collectionService::getGroupCollectionByIds($idGroup,$idCol);

        $resultAfter  = mysqli_query($GLOBALS['link'],"SELECT * FROM grupo_coleccion");
        $rowsAfter = $resultAfter->num_rows;
        
        $this->assertTrue($result2);
        $this->assertTrue($result);
        $this->assertGreaterThan($rowsBefore,$rowsAfter);
        
        
        $result3 = collectionService::deleteGroupCollectionByIds($idGroup,$idCol);
        
        $this->assertTrue($result3);
        $resultAfterDelete = mysqli_query($GLOBALS['link'],"SELECT * FROM grupo_coleccion");
        $rowsAfterDelete = $resultAfterDelete->num_rows;
        
        $this->assertEquals($rowsBefore,$rowsAfterDelete);
        
    }
}
?>