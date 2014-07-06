<?php
include ("../model/persistence/loginService.php");
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
    * Test to check the function checkLogin true with the administrator
    *
    */
    public function testCheckLoginTrueAdmin() {
        $userName = "71301681W";
        $password = "ADMIN123";
        $passwordEncrypt = md5($password);

        $result = loginService::checkLogin($userName, $passwordEncrypt);
        if($row = mysqli_fetch_assoc($result)){
            $this->assertEquals(1, $row['idUsuario']);
            $this->assertEquals("ADMIN", $row['tipo']);
        }
        $result->close();
    }
    
    /**
    * Test to check the function checkLogin true with a teacher
    *
    */
    public function testCheckLoginTrueTeacher() {
        $userName = "86390391E";
        $password = "ALVARO123";
        $passwordEncrypt = md5($password);

        $result = loginService::checkLogin($userName, $passwordEncrypt);
        if($row = mysqli_fetch_assoc($result)){
            $this->assertEquals(2, $row['idUsuario']);
            $this->assertEquals("PROFESOR", $row['tipo']);
        }
        $result->close();
    }
    
    /**
    * Test to check the function checkLogin true with a student
    *
    */
    public function testCheckLoginTrueStudent() {
        $userName = "19264724Q";
        $password = "ALUMNO123";
        $passwordEncrypt = md5($password);

        $result = loginService::checkLogin($userName, $passwordEncrypt);
        if($row = mysqli_fetch_assoc($result)){
            $this->assertEquals(7, $row['idUsuario']);
            $this->assertEquals("ALUMNO", $row['tipo']);
        }
        $result->close();
    }
    
    /**
    * Test to check the function checkLogin false
    *
    */
    public function testCheckLoginFalse() {
        $userName = "19264724Q";
        $password = "TEST123";
        $passwordEncrypt = md5($password);

        $result = loginService::checkLogin($userName, $passwordEncrypt);
        $row=mysqli_fetch_assoc($result);
        $this->assertEquals(0, $row);
        $result->close();
    }
        
}

?>