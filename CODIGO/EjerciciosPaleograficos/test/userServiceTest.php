<?php
include ("../model/persistence/userService.php");
include_once("../model/User.php");
/**
* Class to test the UserService class
*
* @package  test
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
* @backupGlobals disabled
*/
class userServiceTest extends PHPUnit_Framework_TestCase {
    
    /**
    * Test to check the function getUserByName with an empty name 
    *
    */
    public function testGetUserByNameEmpty() {
        $userName = "";

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
        $userName = "63297008L";

        $result = userService::getUserByName($userName);
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(1, $rows);
    }
    
    /**
    * Test to check the function getUserById 
    *
    */
    public function testGetUserById() {
        $idUser = 7;

        $user = userService::getUserById($idUser);
        if($user!=null){
            $this->assertEquals("HECTOR", $user->getName());
            $this->assertEquals("PELAYO ALAMO", $user->getSurnames());
            $this->assertEquals("hpa@alu.ubu.es", $user->getEmail());
        } 
    }
    
    /**
    * Test to check the function checkNameNotRepeat true 
    *
    */
    public function testCheckNameNotRepeatTrue() {
        $nameUser="63297008L";
        $idUser=13;

        $result = userService::checkNameNotRepeat($nameUser, $idUser);
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(0, $rows);
    }
    
    /**
    * Test to check the function checkNameNotRepeat false 
    *
    */
    public function testCheckNameNotRepeatFalse() {
        $nameUser="63297008L";
        $idUser=12;

        $result = userService::checkNameNotRepeat($nameUser, $idUser);
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(1, $rows);
    }
    
    /**
    * Test to check the function insertUser
    *
    */
    public function testInsertUser() {
        $dniUser = "21483098V";
        $passwordUser = "TEST1234";
        $nameUser = "NAME";
        $surnamesUser = "SURNAMES";
        $emailUser = "email@email.com";
        
        $resultBeforeUser  = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario WHERE tipo='ALUMNO'");
        $rowsBeforeUser = $resultBeforeUser->num_rows;
        $resultBeforeUser->close();
        
        
        userService::insertUser($dniUser, $passwordUser, $nameUser, $surnamesUser, $emailUser);

        $resultAfterUser  = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario WHERE tipo='ALUMNO'");
        $rowsAfterUser = $resultAfterUser->num_rows;
        $resultAfterUser->close();


        $this->assertGreaterThan($rowsBeforeUser,$rowsAfterUser);        
    }

    /**
    * Test to check the function insertTeacher
    *
    */
    public function testInsertTeacher() {        
        $dniTeacher = "67302109S";
        $passwordTeacher = "TEST1234";
        $nameTeacher = "NAME";
        $surnamesTeacher = "SURNAMES";
        $emailTeacher = "email@email.com";
        
        $resultBeforeTeacher  = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario WHERE tipo='PROFESOR'");
        $rowsBeforeTeacher = $resultBeforeTeacher->num_rows;
        $resultBeforeTeacher->close();
        
        userService::insertTeacher($dniTeacher, $passwordTeacher, $nameTeacher, $surnamesTeacher, $emailTeacher);
        
        $resultAfterTeacher  = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario WHERE tipo='PROFESOR'");
        $rowsAfterTeacher = $resultAfterTeacher->num_rows;
        $resultAfterTeacher->close();

        $this->assertGreaterThan($rowsBeforeTeacher,$rowsAfterTeacher);
        
    }

    /**
    * Test to check the functions deleteById
    *
    */
    public function testDeleteById() {
        $dniUser="21483098V";
        $dniTeacher="67302109S";
        
        $resultBeforeUser  = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario WHERE tipo='ALUMNO'");
        $rowsBeforeUser = $resultBeforeUser->num_rows;
        $resultBeforeUser->close();
        
        $resultBeforeTeacher  = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario WHERE tipo='PROFESOR'");
        $rowsBeforeTeacher = $resultBeforeTeacher->num_rows;
        $resultBeforeTeacher->close();
        
        $user = userService::getUserByName($dniUser);
        if($user){
            $idUser = $user->fetch_assoc();
            $idUser = $idUser['idUsuario'];
        }
        $user->close();
        
        $teacher = userService::getUserByName($dniTeacher);
        if($teacher){
            $idTeacher = $teacher->fetch_assoc();
            $idTeacher = $idTeacher['idUsuario'];
        }
        $teacher->close();
        
        userService::deleteById($idUser);
        userService::deleteById($idTeacher);
        
        $resultAfterUser  = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario WHERE tipo='ALUMNO'");
        $rowsAfterUser = $resultAfterUser->num_rows;
        $resultAfterUser->close();
        
        $resultAfterTeacher  = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario WHERE tipo='PROFESOR'");
        $rowsAfterTeacher = $resultAfterTeacher->num_rows;
        $resultAfterTeacher->close();
        
        $this->assertLessThan($rowsBeforeTeacher,$rowsAfterTeacher);
        $this->assertLessThan($rowsBeforeUser,$rowsAfterUser);
    }

    /**
    * Test to check the function updateById
    *
    */
    public function testUpdateById() {
        $dni = "63297008L";
        $name = "PAULA";
        $surnames = "OLMEDO ARMENTEROS";
        $email = "paula@email.com";
        $idUser=13;

        userService::updateById($name, $surnames, $email, $dni, $idUser);
        $user = userService::getUserByName($dni);
        if($user){
            $newEmail = $user->fetch_assoc();
            $newEmail = $newEmail['email'];
        }
        $user->close();
        
        $this->assertEquals($newEmail, $email);
    }
        
}

?>