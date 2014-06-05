<?php
include('/acceso_db.php');
class Document{
    private $idDocument;
    private $name;
    private $description;
    private $date;
    private $writingType;
    private $image;
    private $transcription;
    private $exercises;

    
    public function Document($idDocument,$name,$description,$date,$writingType,$image,$transcription){
        $this->$idDocument = $idDocument;
        $this->$name = $name;
        $this->$description = $description;
        $this->$date = $date;
        $this->$writingType = $writingType;
        $this->$image = $image;
        $this->$transcription = $transcription;
    }
    
    
    public static function getDocumentsStudentByCollection($idUser,$idCollection){
        $documents = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario WHERE usuario.idUsuario= '".$idUser."'");
        if($documents){
            if($row=mysqli_fetch_assoc($documents)){
                $document = new Document()
                
            } 
        }
        mysqli_free_result($result);
        return $user;
    }
}
?>