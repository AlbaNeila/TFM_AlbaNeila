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
    * Test that verifies the function getByName returns nothing with a name collection that isn't exist
    *
    */
    public function testGetByNameEmpty() {
        $collectionName = "";

        $result = collectionService::getByName($collectionName);
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(0, $rows);
    }
    
    /**
    * Test that verifies the function getByName returns the correct idCollection of Pública collection
    *
    */
    public function testGetByNamePublic() {
        $collectionName = "Pública";
        $idCollection="";

        $result = collectionService::getByName(utf8_decode($collectionName));
        if($result){
            $idCollection = $result->fetch_assoc();
        }


        $this->assertEquals(1, $idCollection['idColeccion']);
    }
    
    /**
    * Test that verifies the function checkNameNotRepeat returns that a collection name isn't already exist
    *
    */
    public function testCheckNameNotRepeatTrue(){
        $collectionName = "Nueva colección";
        $idCollection=6;

        $result = collectionService::checkNameNotRepeat($collectionName, $idCollection);
        $rows = $result ->num_rows;
        
        $this->assertEquals(0, $rows);
        
        //Clean database
        
    }
    
    /**
    * Test that verifies the function checkNameNotRepeat returns that a collection name is already exist
    *
    */
    public function testCheckNameNotRepeatFalse(){
        $collectionName = "Siglo X";
        $idCollection=6;

        $result = collectionService::checkNameNotRepeat($collectionName, $idCollection);
        $rows = $result ->num_rows;


        $this->assertEquals(1, $rows);
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
        
        collectionService::insertCollection(utf8_decode($collectionName), utf8_decode($collectionDescription));

        $resultAfter  = mysqli_query($GLOBALS['link'],"SELECT * FROM coleccion");
        $rowsAfter = $resultAfter->num_rows;


        $this->assertGreaterThan($rowsBefore,$rowsAfter);
        
        collectionService::deleteById(7);
        
        $resultAfterDelete = mysqli_query($GLOBALS['link'],"SELECT * FROM coleccion");
        $rowsAfterDelete = $resultAfterDelete->num_rows;
        
        $this->assertEquals($rowsBefore,$rowsAfterDelete);
        
    }
    
    /**
    * Test that verifies the function insertCollection insert a collection and the function deleteById delete it 
    *
    */
    public function testUpdateById(){
        $collectionName = "Siglo XXI";
        $collectionDescription="Documentos pertenecientes al siglo XXI";
        $idCollection=3;
        
        $resultBefore  = mysqli_query($GLOBALS['link'],"SELECT * FROM coleccion");
        $rowsBefore = $resultBefore->num_rows;
        
        collectionService::updateById($collectionName, $collectionDescription, $idCollection);
        $result = collectionService::getByName($collectionName);
        if($result){
            $idNewCollection = $result->fetch_assoc();
        }

        $this->assertEquals($idNewCollection['idColeccion'],$idCollection);
        
    }
    
    /**
    * Test that verifies the function insertGroupCollection insert a new row, the function getGroupCollectionByIds return the row and the function deleteGroupCollectionByIds delete it 
    *
    */
    public function testInsertGetDeleteGroupCollection(){
        $idCollection = 5;
        $idGroup = 3;
        
        $resultBefore  = mysqli_query($GLOBALS['link'],"SELECT * FROM grupo_coleccion");
        $rowsBefore = $resultBefore->num_rows;
        
        collectionService::insertGroupCollection($idGroup, $idCollection);

        $resultAfter  = mysqli_query($GLOBALS['link'],"SELECT * FROM grupo_coleccion");
        $rowsAfter = $resultAfter->num_rows;


        $this->assertGreaterThan($rowsBefore,$rowsAfter);
        
        $result = collectionService::getGroupCollectionByIds($idGroup, $idCollection);
        $idGroupInserted = $result->fetch_assoc();
        
        $this->assertEquals($idGroup,$idGroupInserted['idGrupo']);
        
        collectionService::deleteGroupCollectionByIds($idGroup, $idCollection);
        
        $resultAfterDelete = mysqli_query($GLOBALS['link'],"SELECT * FROM grupo_coleccion");
        $rowsAfterDelete = $resultAfterDelete->num_rows;
        
        $this->assertEquals($rowsBefore,$rowsAfterDelete);
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

}
?>