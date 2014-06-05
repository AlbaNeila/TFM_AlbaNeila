<?php
include('/acceso_db.php');
class Exercise{
    private $idExercise;
    private $name;
    private $checkTranscription;
    private $targetType;
    private $targetValue;
    private $idDocument;
    private $idDificult;

    
    public function __construct($idExercise,$name,$checkTranscription,$targetType,$targetValue,$idDocument,$idDificult){
        $this->$idExercise = $idExercise;
        $this->$name = $name;
        $this->$checkTranscription = $checkTranscription;
        $this->$targetType = $targetType;
        $this->$targetValue = $targetValue;
        $this->$idDocument = $idDocument;
        $this->$idDificult = $idDificult;
    }
    
    
    public static function getById($idUser){
        $user = new User();
        $result = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario WHERE usuario.idUsuario= '".$idUser."'");
        if($result){
            if($row=mysqli_fetch_assoc($result)){
                $user->idUser = $idUser;
                $user->fields['name']= $row['name'];
                $user->fields['surnames']= $row['surnames'];
                $user->fields['dni']= $row['dni'];
                $user->fields['password']= $row['password'];
                $user->fields['email']= $row['email'];
                $user->fields['type']= $row['type'];
            } 
        }
        mysqli_free_result($result);
        return $user;
    }
}
?>