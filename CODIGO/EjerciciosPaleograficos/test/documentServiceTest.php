<?php
include ("../model/persistence/documentService.php");
/**
* DocumentServiceTest is a class to test the DocumentService class
*
* @package  test
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
* @backupGlobals disabled
*/
class documentServiceTest extends PHPUnit_Framework_TestCase {
    
    /**
    * Test to check the function getById 
    *
    */
    public function testGetById() {
       $idDoc = 1;

        $doc = documentService::getById($idDoc);
        if($doc!=null){
            $this->assertEquals("../img_xml/2_1.jpg", $doc->getImageDocument());
            $this->assertEquals("../img_xml/2_1.xml", $doc->getTranscriptionDocument());
            $this->assertEquals("Doc HI-2734", $doc->getNameDocument());
            $this->assertEquals("HI-2734", $doc->getDescriptionDocument());
        } 
    }
    
    /**
    * Test to check the function getByName 
    *
    */
    public function testGetByNameOk() {
       $document = "Doc HI-2734";

        $result = documentService::getByName($document);
        $this->assertTrue($result);
    }
    
    /**
    * Test to check the function getByName 
    *
    */
    public function testGetByNameNOk() {
       $document = "Prueba doc";

        $result = documentService::getByName($document);
        $this->assertFalse($result);
    }
    
    /**
    * Test to check the function checkNameNotRepeat true 
    *
    */
    public function testCheckNameNotRepeatTrue() {
        $nombre="Doc HI-2734";
        $idDoc=2;

        $result = documentService::checkNameNotRepeat($nombre, $idDoc);
        $this->assertTrue($result);
    }
    
    /**
    * Test to check the function checkNameNotRepeat false 
    *
    */
    public function testCheckNameNotRepeatFalse() {
        $nombre="Prueba doc";
        $idDoc=2;

        $result = documentService::checkNameNotRepeat($nombre, $idDoc);
        $this->assertFalse($result);
    }
    
    /**
    * Test to check the function getFilesById
    */
    public function testGetFilesById() {
        $idDoc=2;
        $image="";
        $transcription="";

        $result = documentService::getFilesById($idDoc);
        if($row = mysqli_fetch_assoc($result)){
            $image = $row['imagen'];
            $transcription = $row['transcripcion'];
        }
        $this->assertEquals("../img_xml/2_2.jpg",$image);
        $this->assertEquals("../img_xml/2_2.xml",$transcription);
    }
    
    /**
    * Test to check the function insertDocument,deleteById
    *
    */
    public function testInsertAndDeleteDocument() {
        $name="doc test";
        $description="description test";
        $date="date test";
        $type="type test";
        
        $resultBeforeUser  = mysqli_query($GLOBALS['link'],"SELECT * FROM documento");
        $rowsBeforeUser = $resultBeforeUser->num_rows;
        $resultBeforeUser->close();
        
        
        $idDocInserted = documentService::insertDocument($name, $description, $date, $type);

        $resultAfterUser  = mysqli_query($GLOBALS['link'],"SELECT * FROM documento");
        $rowsAfterUser = $resultAfterUser->num_rows;
        $resultAfterUser->close();
        
        $resultDelete = documentService::deleteById($idDocInserted);

        $this->assertTrue($resultDelete);
        $this->assertGreaterThan($rowsBeforeUser,$rowsAfterUser);        
    }
    
        /**
    * Test to check the function updateById,updateFilesById
    *
    */
    public function testUpdateById() {
        $name="doc1";
        $description="description1";
        $date="date1";
        $type="type1";
        
        $nombre="doc2";
        $descripcion="description2";
        $fecha="date2";
        $tipoEscritura="type2";
        $uploadimg="img.jpg";
        $uploadxml="trns.xml";
      
        $idDocInserted = documentService::insertDocument($name, $description, $date, $type);
        $result = documentService::updateById($idDocInserted, $nombre, $descripcion, $fecha, $tipoEscritura);
        documentService::updateFilesById($idDocInserted, $uploadimg, $uploadxml);
        
        $doc = documentService::getById($idDocInserted);
        if($doc!=null){
            $this->assertEquals($fecha, $doc->getDateDocument());
            $this->assertEquals($tipoEscritura, $doc->getTypeWritingDocument());
            $this->assertEquals($nombre, $doc->getNameDocument());
            $this->assertEquals($descripcion, $doc->getDescriptionDocument());
        } 
        
        $this->assertEquals($uploadimg, $doc->getImageDocument());
        $this->assertEquals($uploadxml, $doc->getTranscriptionDocument());
        
        $resultDelete = documentService::deleteById($idDocInserted);

        $this->assertTrue($resultDelete);     
    }

    /**
    * Test to check the function insertColeccionDocumento
    *
    */
    public function testInsertColeccionDocumento() {
        $idCol=1;
        $idDoc=1;
        
        $resultBeforeUser  = mysqli_query($GLOBALS['link'],"SELECT * FROM coleccion_documento");
        $rowsBeforeUser = $resultBeforeUser->num_rows;
        $resultBeforeUser->close();
        
        
        $result = documentService::insertColeccionDocumento($idCol, $idDoc);
        $this->assertTrue($result);

        $resultAfterUser  = mysqli_query($GLOBALS['link'],"SELECT * FROM coleccion_documento");
        $rowsAfterUser = $resultAfterUser->num_rows;
        $resultAfterUser->close();


        $this->assertGreaterThan($rowsBeforeUser,$rowsAfterUser);  
        
        $resultDelete = documentService::deleteColleccionDocumentoByIds($idCol, $idDoc);
        $this->assertTrue($resultDelete);      
    }
}
?>