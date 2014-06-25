<?php
include ("../model/persistence/groupService.php");
/**
* Class to test the GroupService class
*
* @package  test
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
* @backupGlobals disabled
*/
class groupServiceTest extends PHPUnit_Framework_TestCase {
    
    /**
    * Test to check the function getByName with an empty name 
    *
    */
    public function testGetByNameEmpty() {
        $groupName = "";

        $result = groupService::getByName(utf8_decode($groupName));
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(0, $rows);
    }
    
    /**
    * Test to check the function getByName with an existing name
    *
    */
    public function testGetByNameTrue() {
        $groupName = "GRUPO 1 (M)";

        $result = groupService::getByName(utf8_decode($groupName));
        $rows=$result->num_rows;
       

        $this->assertEquals(1, $rows);
    }
    
    /**
    * Test to check the function checkNameNotRepeat true
    *
    */
    public function testCheckNameNotRepeatTrue() {
        $groupName = "GRUPO NUEVO";
        $idGroup=1;

        $result = groupService::checkNameNotRepeat(utf8_decode($groupName),$idGroup);
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(0, $rows);
    }
    
    /**
    * Test to check the function checkNameNotRepeat false
    *
    */
    public function testCheckNameNotRepeatFalse() {
        $groupName = "GRUPO 2 (M)";
        $idGroup=2;

        $result = groupService::checkNameNotRepeat(utf8_decode($groupName),$idGroup);
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(1, $rows);
    }
    
    /**
    * Test to check the function getDescriptionById true
    *
    */
    public function testGetDescriptionByIdTrue() {
        $groupDescription = "Grupo 1  (turno T)";
        $idGroup=2;

        $result = groupService::getDescriptionById($idGroup);
        if($result){
            $newGroupDescription = $result->fetch_assoc();
        }
        $result->close();

        $this->assertEquals(utf8_encode($newGroupDescription['descripcion']), utf8_encode($groupDescription));
    }
    
    /**
    * Test to check the function getDescriptionById false
    *
    */
    public function testGetDescriptionByIdFalse() {
        $groupDescription = "Nueva descripción";
        $idGroup=2;

        $result = groupService::getDescriptionById($idGroup);
        if($result){
            $newGroupDescription = $result->fetch_assoc();
        }
        $result->close();

        $this->assertNotEquals($newGroupDescription['descripcion'], $groupDescription);
    }
    
    /**
    * Test to check the functions insertGroup and deleteById
    *
    */
    public function testInsertDeleteGroup() {
        $grupo = "GRUPO 4 (M)";
        $descripcion = "Nuevo grupo insertado";
        $usuarioCreador = 1;
        
        $resultBefore  = mysqli_query($GLOBALS['link'],"SELECT * FROM grupo");
        $rowsBefore = $resultBefore->num_rows;
        $resultBefore->close();
        
        groupService::insertGroup($grupo, $descripcion, $usuarioCreador);

        $resultAfter  = mysqli_query($GLOBALS['link'],"SELECT * FROM grupo");
        $rowsAfter = $resultAfter->num_rows;
        $resultAfter->close();

        $this->assertGreaterThan($rowsBefore,$rowsAfter);
        
        $result = groupService::getByName(utf8_decode($grupo));
        $idGroupInserted = $result->fetch_assoc();
        groupService::deleteById($idGroupInserted['idGrupo']);
        $result->close();
        
        $resultAfterDelete = mysqli_query($GLOBALS['link'],"SELECT * FROM grupo");
        $rowsAfterDelete = $resultAfterDelete->num_rows;
        $resultAfterDelete->close();
        
        $this->assertEquals($rowsBefore,$rowsAfterDelete);
    }
    
    /**
    * Test to check the function updateById 
    *
    */
    public function testUpdateById() {
        $groupName = "GRUPO 3 (M)";
        $description="Grupo 3 (turno M)";
        $idGroup = 4;

        groupService::updateById($groupName, $description, $idGroup);
        $result2 = groupService::getDescriptionById($idGroup);
        if($result2){
            $newGroupDescription = $result2->fetch_assoc();
        }
        $result2->close();

        $this->assertEquals($newGroupDescription['descripcion'], $description);
    }
    
    /**
    * Test to check the functions insertUsuarioGrupoSolicitud and deleteUsuarioGrupoByIds
    *
    */
    public function testInsertDeleteUserGroup() {
        $idUser = "15";
        $idGroup = "3";
        
        $resultBefore  = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario_grupo");
        $rowsBefore = $resultBefore->num_rows;
        $resultBefore->close();
        
        groupService::insertUsuarioGrupoSolicitud($idUser, $idGroup);

        $resultAfter  = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario_grupo");
        $rowsAfter = $resultAfter->num_rows;
        

        $this->assertGreaterThan($rowsBefore,$rowsAfter);
        
        $resutlGet = groupService::getUsuarioGrupoByIds($idGroup, $idUser);
        if($resutlGet){
            $newIdGroup= $resutlGet->fetch_assoc();
        }
        $this->assertEquals($newIdGroup['idGrupo'],$idGroup);
        $resutlGet->close();
        
        groupService::deleteUsuarioGrupoByIds($idGroup, $idUser);
        
        $resultAfterDelete = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario_grupo");
        $rowsAfterDelete = $resultAfterDelete->num_rows;
        $resultAfterDelete->close();
        
        $this->assertEquals($rowsBefore,$rowsAfterDelete);
    }
    
}

?>