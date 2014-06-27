<?php
include ("../model/persistence/userService.php");
/**
* Class to test the LoginService class
*
* @package  test
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
* @backupGlobals disabled
*/
class loginServiceTest extends PHPUnit_Framework_TestCase {
    
    /**
    * Test to check the function getUserByName with an empty name 
    *
    */
    public function testCheckLoginTrue() {


        $result = userService::getUserByName($userName);
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(0, $rows);
    }
    
    /**
    * Test to check the function getUserByName with an existing name 
    *
    */
    public function testGetUserByNameTrue() {
        $userName = "95144194W";

        $result = userService::getUserByName($userName);
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(1, $rows);
    }

        
}

?>