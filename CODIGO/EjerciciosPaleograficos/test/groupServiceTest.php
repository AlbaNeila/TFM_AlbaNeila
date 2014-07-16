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
    
        $this->assertFalse($result);
    }
    
    /**
    * Test to check the function getByName with an existing name
    *
    */
    public function testGetByNameTrue() {
        $groupName = "2º PALEOGRAFÍA";
        $idGroup = 0;

        $result = groupService::getByName(utf8_decode($groupName));
        if($result!= false){
            $idGroup = $result;
        }

        $this->assertEquals(3, $idGroup);
    }
    
    /**
    * Test to check the function checkNameNotRepeat true
    *
    */
    public function testCheckNameNotRepeatTrue() {
        $groupName = "GRUPO NUEVO";
        $idGroup=1;

        $result = groupService::checkNameNotRepeat(utf8_decode($groupName),$idGroup);

        $this->assertFalse($result);
    }
    
    
    /**
    * Test to check the function checkNameNotRepeat false
    *
    */
    public function testCheckNameNotRepeatFalse() {
        $groupName = "2º PALEOGRAFÍA";
        $idGroup=1;

        $result = groupService::checkNameNotRepeat(utf8_decode($groupName),$idGroup);

        $this->assertTrue($result);
    }
    
    /**
    * Test to check the function getDescriptionById true
    *
    */
    public function testGetDescriptionByIdTrue() {
        $idGroup=4;
        $newGroupDescription="";

        $result = groupService::getDescriptionById($idGroup);
        if($result!=null){
            $newGroupDescription = $result;
        }
        $this->assertEquals(utf8_encode($newGroupDescription), "Grupo de 3º de Paleografía (turno de mañana)");
    }
        

    /**
    * Test to check the function getDescriptionById false
    *
    */
    public function testGetDescriptionByIdFalse() {
        $groupDescription = "Nueva descripción";
        $idGroup=2;
        $newGroupDescription="";

        $result = groupService::getDescriptionById($idGroup);
        if($result!=null){
            $newGroupDescription = $result;
        }

        $this->assertNotEquals($newGroupDescription, $groupDescription);
    }
    
     
    /**
    * Test to check the functions insertGroup and deleteById
    *
    */
    public function testInsertDeleteGroup() {
        $grupo = "GRUPO nuevo (M)";
        $descripcion = "Nuevo grupo insertado";
        $usuarioCreador = 1;
        
        $resultBefore  = mysqli_query($GLOBALS['link'],"SELECT * FROM grupo");
        $rowsBefore = $resultBefore->num_rows;
        
        $idGroupInserted = groupService::insertGroup($grupo, $descripcion, $usuarioCreador);

        $resultAfter  = mysqli_query($GLOBALS['link'],"SELECT * FROM grupo");
        $rowsAfter = $resultAfter->num_rows;

        $this->assertGreaterThan($rowsBefore,$rowsAfter);

        groupService::deleteById($idGroupInserted);
        
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
        $groupName = "1º PALEOGRAFÍA";
        $description="GRUPO ACTUALIZADO";
        $idGroup = 2;

        groupService::updateById($groupName, $description, $idGroup);
        $result2 = groupService::getDescriptionById($idGroup);
        if($result2!=NULL){
            $newGroupDescription = $result2;
        }
        
        $this->assertEquals($newGroupDescription, $description);
    }
    
        /**
    * Test to check the functions insertUsuarioGrupoSolicitud and deleteUsuarioGrupoByIds
    *
    */
    public function testInsertDeleteUserGroup() {
        $idUser = "11";
        $idGroup = "2";
        
        $resultBefore  = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario_grupo");
        $rowsBefore = $resultBefore->num_rows;
        
        $resultInsert = groupService::insertUserGroupRequest($idUser, $idGroup);

        $resultAfter  = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario_grupo");
        $rowsAfter = $resultAfter->num_rows;
        
        $this->assertTrue($resultInsert);
        $this->assertGreaterThan($rowsBefore,$rowsAfter);
        
        $resutlGet = groupService::getUsuarioGrupoByIds($idGroup, $idUser);
        if($resutlGet){
            $newIdGroup= $resutlGet->fetch_assoc();
        }
        $this->assertEquals($newIdGroup['idGrupo'],$idGroup);
        
        $resultDelete = groupService::deleteUserGroupByIds($idGroup, $idUser);
        
        $resultAfterDelete = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario_grupo");
        $rowsAfterDelete = $resultAfterDelete->num_rows;
        $resultAfterDelete->close();
        
        $this->assertTrue($resultDelete);
        $this->assertEquals($rowsBefore,$rowsAfterDelete);
    }
}

?>