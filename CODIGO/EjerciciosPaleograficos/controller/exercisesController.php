 <?php
    session_start();
    include('../model/acceso_db.php');
    $method = $_POST['method'];
    
    switch($method){
        case 'newExercise':
            newExercise();
            break;
        case 'deleteExercise':
            deleteExercise();
            break;
    }
    
    function newExercise(){
        $flag="";
        $idDocument = mysqli_real_escape_string($GLOBALS['link'],$_POST['idDocument']);
        $idCollection = mysqli_real_escape_string($GLOBALS['link'],$_POST['idCollection']);
        $name=mysqli_real_escape_string($GLOBALS['link'],$_POST['name']);
        $dificult=mysqli_real_escape_string($GLOBALS['link'],$_POST['dificult']);
        $correction=mysqli_real_escape_string($GLOBALS['link'],$_POST['correction']);
        $target=mysqli_real_escape_string($GLOBALS['link'],$_POST['target']);
        if($target==0){
            $target='% palabras acertadas';
        }else{
            $target='nº máximo de fallos';
        }
        $targetnum=mysqli_real_escape_string($GLOBALS['link'],$_POST['targetnum']);
        $groups = $_POST["groups"];
        $groups = json_decode("$groups",true);
        
        $result = mysqli_query($GLOBALS['link'],"SELECT ejercicio.nombre FROM ejercicio WHERE ejercicio.nombre= '".utf8_decode($name)."'");
        if($result!=FALSE){
            if(!$row=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otro documento con el mismo nombre
                 $insert = mysqli_query($GLOBALS['link'],"INSERT INTO ejercicio (ejercicio.nombre, ejercicio.comprobarTranscripcion,ejercicio.tipo_objetivo,ejercicio.valor_objetivo,ejercicio.idDocumento,ejercicio.idDificultad) VALUES ('".$name."','".$correction."','".$target."','".$targetnum."','".$idDocument."','".$dificult."')");
                 if(!$insert){
                     $flag = 0;
                 }else{
                     //Insertar en la ternaria
                      $result2 = mysqli_query($GLOBALS['link'],"SELECT ejercicio.idEjercicio FROM ejercicio WHERE ejercicio.nombre= '".utf8_decode($name)."'");
                      $result2 = mysqli_fetch_assoc($result2);
                      $idEjercicio = $result2['idEjercicio'];
                      foreach($groups as $idGroup){
                        $insert2 = mysqli_query($GLOBALS['link'],"INSERT INTO grupo_ejercicio_coleccion (grupo_ejercicio_coleccion.idGrupo, grupo_ejercicio_coleccion.idEjercicio,grupo_ejercicio_coleccion.idColeccion) VALUES ('".$idGroup."','".$idEjercicio."','".$idCollection."')");
                        if(!$insert2){
                            $flag = 0;
                        }else{
                            $flag=1;
                        }
                      }
                 }
            }else{
                $flag = 2;//Otro ejercicio con el mismo nombre
            }
        }
        echo $flag;
    }

    function deleteExercise(){
        $idEj = mysqli_real_escape_string($GLOBALS['link'],$_POST['idEj']);
        
       $result = mysqli_query($GLOBALS['link'],"DELETE FROM ejercicio WHERE ejercicio.idEjercicio= '".$idEj."'");
        
        if($result!=FALSE){
                    echo 1; //Delete grupo OK
        }
        else{
            echo 0; //Error
        }
    }
    
?> 