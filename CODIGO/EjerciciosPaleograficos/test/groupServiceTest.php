<?php
include ("../model/persistence/groupService.php");
/**
* GroupServiceTest is a class to test the GroupService class
*
* @package  test
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
* @backupGlobals disabled
*/
class groupServiceTest extends PHPUnit_Framework_TestCase {
    
    /**
    * Test that verifies the function getByName 
    *
    */
    public function testGetByNameEmpty() {
        $groupName = "";

        $result = groupServiceTest::getByName($groupName);
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(0, $rows);
    }
    
    /**
    * Test that verifies the function getByName 
    *
    */
    public function testGetByNameTrue() {
        $groupName = "3º PALEOGRAFÍA";

        $result = groupServiceTest::getByName($groupName);
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(1, $rows);
    }
    
    /**
    * Test that verifies the function getByName 
    *
    */
    public function testCheckNameNotRepeat() {
        $groupName = "";

        $result = groupServiceTest::getByName($groupName);
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(0, $rows);
    }
    
    
}

?>