<?php
include ("../model/persistence/exerciseService.php");
/**
* ExerciseServiceTest is a class to test the ExerciseService class
*
* @package  test
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
* @backupGlobals disabled
*/
class exerciseServiceTest extends PHPUnit_Framework_TestCase {
    
    /**
    * Test to check the function getByName with an empty name 
    *
    */
    public function testGetByNameEmpty() {
        $nameEj = "";

        $result = exerciseService::getByName($nameEj);
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(0, $rows);
    }
   
       /**
    * Test to check the function getByName with an existing name 
    *
    */
    public function testGetByNameTrue() {
        $nameEj = "Ejercicio HI-2734 (principantes)";

        $result = exerciseService::getByName($nameEj);
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(1, $rows);
    }
    
     /**
    * Test to check the function checkNameNotRepeat true 
    *
    */
    public function testCheckNameNotRepeatTrue() {
        $nameEj = "Test ejercicio";
        $idEj=1;

        $result = exerciseService::checkNameNotRepeat($nameEj, $idEj);
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(0, $rows);
    }
    
    /**
    * Test to check the function checkNameNotRepeat false 
    *
    */
    public function testCheckNameNotRepeatFalse() {
        $nameEj = "Ejercicio HI-2734 (principantes)";
        $idEj=2;

        $result = exerciseService::checkNameNotRepeat($nameEj, $idEj);
        $rows=$result->num_rows;
        $result->close();

        $this->assertEquals(1, $rows);
    }
    
    /**
    * Test to check the function getById 
    *
    */
    public function testGetById() {
        $idEj = 1;
        $ejName="";

        $result = exerciseService::getById($idEj);
        if($row=mysqli_fetch_array($result)){
            $ejName = $row['nombre'];
        } 
        
        $this->assertEquals("Ejercicio HI-2734 (principantes)", $ejName);
    }
    
    /**
    * Test to check the function insertExercise and deleteById 
    *
    */
    public function testInsertAndDeleteExercise() {
        $name="Ejercicio test";
        $correction=1;
        $target="% palabras acertadas";
        $targetnum=30;
        $idDocument=1;
        $dificult=0;

        $result = exerciseService::insertExercise($name, $correction, $target, $targetnum, $idDocument, $dificult);
        $this->assertTrue($result);
        
        $resultId = exerciseService::getByName($name);
        if($row = mysqli_fetch_assoc($resultId)){
            $idEj = $row['idEjercicio'];
        }
        
        $resultDelete = exerciseService::deleteById($idEj);
        
        $this->assertTrue($resultDelete);
    }
    
    /**
    * Test to check the functions updateTipsById, updateTargetById, updateValueTargetById, updateCorrectionModeById, updateNameById
    *
    */
    public function testUpdateConfigurationExercise() {
        $name="Ejercicio test update";
        $correction=1;
        $target="% palabras acertadas";
        $targetnum=30;
        $idDocument=1;
        $dificult=0;

        $result = exerciseService::insertExercise($name, $correction, $target, $targetnum, $idDocument, $dificult);
        $this->assertTrue($result);
        
        $resultId = exerciseService::getByName($name);
        if($row = mysqli_fetch_assoc($resultId)){
            $idEj = $row['idEjercicio'];
        }
        
        exerciseService::updateTipsById(2, $idEj);
        exerciseService::updateValueTargetById(utf8_decode("nº máximo de fallos"), $idEj);
        exerciseService::updateTargetById(50, $idEj);
        exerciseService::updateCorrectionModeById(0, $idEj);
        exerciseService::updateNameById("ejUpdate", $idEj);
        
        $updateEj = exerciseService::getById($idEj);
        if($row2 = mysqli_fetch_assoc($updateEj)){
            $this->assertEquals("ejUpdate",$row2['nombre']);
            $this->assertEquals(0,$row2['comprobarTranscripcion']);
            $this->assertEquals(utf8_decode("nº máximo de fallos"),$row2['tipo_objetivo']);
            $this->assertEquals(50,$row2['valor_objetivo']);
            $this->assertEquals(2,$row2['idDificultad']);
        }
        
        
        $resultDelete = exerciseService::deleteById($idEj);
        
        $this->assertTrue($resultDelete);
    }

    /**
    * Test to check the function getMaxOrder 
    *
    */
    public function testGetMaxOrder() {
        $idCol=6;

        $result = exerciseService::getMaxOrder($idCol);
        if($row=mysqli_fetch_array($result)){
            $maxOrder = $row['max'];
        } 
        
        $this->assertEquals(2, $maxOrder);
    }
    
    /**
    * Test to check the function getNextOrderExercise 
    *
    */
    public function testGetNextOrderExercise() {
        $idCol=6;
        $idUser=6;
        $idEj=9;

        $result = exerciseService::getNextOrderExercise($idUser, $idCol, $idEj);
        if($row=mysqli_fetch_array($result)){
            $nextOrder = $row['orden'];
        } 
        
        $this->assertEquals(1, $nextOrder);
    }
    
    /**
    * Test to check the function getNextExerciseToDo 
    *
    */
    public function testGetNextExerciseToDo() {
        $idCol=6;
        $idUser=6;
        $orden=1;

        $result = exerciseService::getNextExerciseToDo($idUser, $idCol, $orden);
        if($row=mysqli_fetch_array($result)){
            $nextEj = $row['idEjercicio'];
        } 
        
        $this->assertEquals(10, $nextEj);
    }
    
    /**
    * Test to check the function insertGrupoEjercicioColeccion and deleteGrupoEjercicioColeccionByIds
    *
    */
    public function testInsertAndDeleteGroupExerciseCollection() {
        $idGroup = 4;
        $idEj = 6;
        $idCol = 6;
        $order = 3;
        
        $result = exerciseService::insertGrupoEjercicioColeccion($idGroup, $idEj, $idCol, $order);
        $this->assertTrue($result);
        
        
        $resultDelete = exerciseService::deleteGrupoEjercicioColeccionByIds($idGroup, $idCol, $idEj);
        
        $this->assertTrue($resultDelete);
    }
    
    /**
    * Test to check the function updateOrderByIdEj
    *
    */
    public function testUpdateOrderByIdEj() {
        $idEj = 9;
        $order = 2;
        $idEj2 = 10;
        $order2 = 1;

        
        $result = exerciseService::updateOrderByIdEj($order, $idEj);
        $result2 = exerciseService::updateOrderByIdEj($order2, $idEj2);
        
        $this->assertTrue($result);
        $this->assertTrue($result2);
        
        $result = exerciseService::updateOrderByIdEj($order2, $idEj);
        $result2 = exerciseService::updateOrderByIdEj($order, $idEj2);

    }
    
}
?>