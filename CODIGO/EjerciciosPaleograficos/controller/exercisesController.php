<?php
 ob_start();
    session_start();
    include('../model/persistence/exerciseService.php');
    include('../model/persistence/documentService.php');
    
    
    $method = $_POST['method'];
    
    switch($method){
        case 'newExercise':
            newExercise();
            break;
        case 'deleteExercise':
            deleteExercise();
            break;
        case 'updateTips':
            updateTips();
            break;
        case 'updateTarget':
            updateTarget();
            break;
        case 'updateValueTarget':
            updateTarget();
            break;
        case 'updateCorrectionMode':
            updateCorrectionMode();
            break;
        case 'checkUpdateGrid':
            checkUpdateGrid();
            break;
        case 'updatePermissionsGroup':
            updatePermissionsGroup();
            break;
        case 'updateOrder':
            updateOrder();
            break;
        case 'accessEj':
            accessEj();
            break;
        case 'finishEj':
            finishEj();
            break;
        case 'checkUpdateOrder':
            checkUpdateOrder();
            break;
        case 'initExercise':
            initExercise();
            break;
    }
    
    /**
     * Function to add a new Exercise.
     *
     * Insert the new exercise in the database and update the grupo_ejercicio_coleccion table with the permissions of the 'groups' received in the groups post variable.
     * Echo 0 if error
     * Echo 1 if ok
     * Echo 2 if if also exist an exercise with the same name
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version  1.0
     *  @access   public
     *  @return void
     */
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
        
        $result = exerciseService::getByName(utf8_decode($name));
        if($result!=FALSE){
            if(!$row=mysqli_fetch_assoc($result)) {
                 $insert = exerciseService::insertExercise(utf8_decode($name), $correction, utf8_decode($target), $targetnum, $idDocument, $dificult);
                 if(!$insert){
                     $flag = 0;
                 }else{
                      $result2 = exerciseService::getByName(utf8_decode($name));
                      $result2 = mysqli_fetch_assoc($result2);
                      $idEjercicio = $result2['idEjercicio'];
                      foreach($groups as $idGroup){
                        $max = exerciseService::getMaxOrder($idCollection);
                        $max = mysqli_fetch_assoc($max);
                        $order = $max['max']+1;
                        $insert2 = exerciseService::insertGrupoEjercicioColeccion($idGroup, $idEjercicio, $idCollection, $order);
                        if(!$insert2){
                            $flag = 0;
                        }else{
                            $flag=1;
                        }
                      }
                 }
            }else{
                $flag = 2;
            }
        }
        echo $flag;
    }
    
    /**
     * Function to delete an Exercise.
     *
     * Delete an exercise form the data base with the id exercise received in the 'idEj' post variable.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function deleteExercise(){
       $idEj = mysqli_real_escape_string($GLOBALS['link'],$_POST['idEj']);
        
       $result = exerciseService::deleteById($idEj);
        
        if($result!=FALSE){
                    echo 1;
        }
        else{
            echo 0;
        }
    }
    
    /**
     * Function to update the number of tips (dificult degree) of an exercise.
     *
     * Update the idDificultad value from the database of the id exercise received in the 'idEj' post variable.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function updateTips(){
        $idEj = mysqli_real_escape_string($GLOBALS['link'],$_POST['idEj']);
        $value= mysqli_real_escape_string($GLOBALS['link'],$_POST['value']);
        
        $result = exerciseService::updateTipsById($value, $idEj);
        if($result!=FALSE){
                    echo 1; 
        }
        else{
            echo 0; //Error
        }
    }
    
    /**
     * Function to update the type of target of an exercise.
     *
     * Update the type of target value from the database of the id exercise received in the 'idEj' post variable.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function updateTarget(){
        $idEj = mysqli_real_escape_string($GLOBALS['link'],$_POST['idEj']);
        $value= mysqli_real_escape_string($GLOBALS['link'],$_POST['value']);
        
        $result = exerciseService::updateTargetById(utf8_decode($value), $idEj);
        if($result!=FALSE){
                    echo 1; 
        }
        else{
            echo 0; //Error
        }
    }
    
    /**
     * Function to update the value of the target of an exercise.
     *
     * Update the value of the target from the database of the id exercise received in the 'idEj' post variable.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function updateValueTarget(){
        $idEj = mysqli_real_escape_string($GLOBALS['link'],$_POST['idEj']);
        $value= mysqli_real_escape_string($GLOBALS['link'],$_POST['value']);
        if($value==0){
            $value='% palabras acertadas';
        }else{
            $value=('nº máximo de fallos');
        }
        
        $result = exerciseService::updateValueTargetById(utf8_decode($value), $idEj);
        if($result!=FALSE){
                    echo 1;
        }
        else{
            echo 0;
        }
    }
    
    /**
     * Function to update the correction mode of an exercise.
     *
     * Update the correction mode from the database of the id exercise received in the 'idEj' post variable.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function updateCorrectionMode(){
        $idEj = mysqli_real_escape_string($GLOBALS['link'],$_POST['idEj']);
        $value= mysqli_real_escape_string($GLOBALS['link'],$_POST['value']);
        
        $result = exerciseService::updateCorrectionModeById($value, $idEj);
        if($result!=FALSE){
                    echo 1; 
        }
        else{
            echo 0;
        }
    }
    
    /**
     * Function to check if the Exercise can be update and if it's OK update it.
     *
     * Check that the name of the exercise it's not repeat and update the Exercise information with the new data receive in the row post variable.
     * Echo 0 if if also exist an exercise with the same name
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function checkUpdateGrid(){
        $row = $_POST["row"];
        $row = json_decode("$row",true);
        
        $result = exerciseService::checkNameNotRepeat(utf8_decode($row[1]), $row[0]);
        
        if($result!=FALSE){
            if(!$fila=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otro ejercicio con el mismo nombre, por lo que actualizamos la fila
                $result2 = exerciseService::updateNameById(utf8_decode($row[1]), $row[0]);
                if($result2!=FALSE)
                    echo 1;
            }
            else{
                echo 0;
            }
        }
        else{
            echo 0;
        }
    }
    
    /**
     * Function to update the group access permissions to an exercise in a collection.
     *
     * Update the grupo_ejercicio_coleccion table with the permissions of the 'groups' received in the groups post variable.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function updatePermissionsGroup(){
        $groups = $_POST["groups"];
        $groups= json_decode("$groups",true);
        $permissions = $_POST["permissions"];
        $permissions= json_decode("$permissions",true);
        $idEj = $_POST['idEj'];
        $idCol = $_POST['idCol'];
        
        $cont=0;
        $flag=1;
        
        foreach($groups as $group){
            $result = exerciseService::getGrupoEjercicioColeccionByIds($group, $idEj, $idCol);
            if($permissions[$cont]==true){
                if(!$fila=mysqli_fetch_assoc($result)){//Si no hay filas -> Insert
                     $max = exerciseService::getMaxOrder($idCol);
                     $max = mysqli_fetch_assoc($max);
                     $order = $max['max']+1;
                     $insert = exerciseService::insertGrupoEjercicioColeccion($group, $idEj, $idCol, $order);
                     if(!$insert){
                         $flag = 0;
                     }
                }
            }
            else{
                if($fila=mysqli_fetch_assoc($result)){//Si hay filas -> Delete
                    $delete = exerciseService::deleteGrupoEjercicioColeccionByIds($group, $idCol, $idEj);
                    if(!$delete){
                         $flag = 0;
                     }
                }
            }
            $cont++;
        }
        echo $flag;
    }
    
    /**
     * Function to update the order of an exercise.
     *
     * Update the order of an exercise exchanging the value between two exercises.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function updateOrder(){
        $idEjUp = mysqli_real_escape_string($GLOBALS['link'],$_POST['idEjUp']);
        $orderUp= mysqli_real_escape_string($GLOBALS['link'],$_POST['orderUp']);

        $idEjDown = mysqli_real_escape_string($GLOBALS['link'],$_POST['idEjDown']);
        $orderDown= mysqli_real_escape_string($GLOBALS['link'],$_POST['orderDown']);
        
        $result1 = exerciseService::updateOrderByIdEj($orderDown, $idEjUp);
        $result2 = exerciseService::updateOrderByIdEj($orderUp, $idEjDown);
        if($result1 && $result2){
            echo 1;
        }else{
            echo 0;
        }
    }
    
    /**
     * Function to check if the order of an exercise can be update.
     *
     * Check if some exercise of the collection with the id received in the 'idCollection' post variable is already used by a student.
     * Echo 0 if the exercise can be updated
     * Echo 1 if the exercise can't be updated
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version 1.0
     *  @access public
     *  @return void
     */
    function checkUpdateOrder(){
        $idCollection=$_POST['idCollection'];
        $noUpdate=0;
        
        $result = exerciseService::getGrupoEjercicioColeccionByIdCol($idCollection);
        while($fila=mysqli_fetch_assoc($result)){
            $result2 = exerciseService::getUsuarioEjercicioByIdEj($fila['idEjercicio']);
            if($result2){
                if($row=mysqli_fetch_assoc($result2)){
                    $noUpdate=1;
                }
            }
        }
        echo $noUpdate;
    }
    
    /**
     * Function to access to an exercise by a student.
     *
     * Get the document and exercise information and the path to the image of the document.
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version  1.0
     *  @access   public
     *  @return void
     */
    function accessEj(){
        $idDocument = $_POST['idDocument'];
        $idExercise = $_POST['idExercise'];
        
        $document = documentService::getById($idDocument);
        $result2 = exerciseService::getById($idExercise);
        if($document!=null){
                $imagen = utf8_encode($document->getImageDocument());
                $nombre = utf8_encode($document->getNameDocument());
                $descripcion = utf8_encode($document->getDescriptionDocument());
                $fecha = utf8_encode($document->getDateDocument());
                $tipoEscritura = utf8_encode($document->getTypeWritingDocument());
                $transcriptionFile = utf8_encode($document->getTranscriptionDocument());
                
                if($result2!=FALSE){
                    $row2=mysqli_fetch_assoc($result2);
                    $nombreej = utf8_encode($row2['nombre']);
                    $comprobarTranscripcion = utf8_encode($row2['comprobarTranscripcion']);
                    $tipoObjetivo = utf8_encode($row2['tipo_objetivo']);
                    $valorObjetivo = utf8_encode($row2['valor_objetivo']);
                    $idDificultad = utf8_encode($row2['idDificultad']);
                    
                    $res= 1;
                }else{
                    $res= 0;
                }
        }else{
            $res= 0;
        }
        
        $data = array(
            "result"=>$res,
            "image"=>$imagen,
            "nombre"=> $nombre,
            "descripcion"=> $descripcion,
            "fecha"=> $fecha,
            "tipoEscritura" => $tipoEscritura,
            "nombreej" => $nombreej,
            "comprobarTranscripcion" => $comprobarTranscripcion,
            "tipoObjetivo" => $tipoObjetivo,
            "valorObjetivo" => $valorObjetivo,
            "idDificultad" => $idDificultad
        );
        $outputdata = json_encode($data);
        
        print($outputdata);  
    }
    
    /**
     * Function to update the exercise list of a student when the sudent finish an exercise.
     *
     * If the exercise has been passed, will found the next exercise to do and this will be activated.
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version  1.0
     *  @access public
     *  @return void
     */
    function finishEj(){
        $superado = $_POST['superado'];
        $idExercise = $_POST['idExercise'];
        $idCollection = $_POST['idCollection'];
        
        $result1 = exerciseService::updateUsuarioEjercicioSuperadoByIds($superado, $idExercise, $_SESSION['usuario_id']);
        if($result1){
            if($superado==1){
                $result2 = exerciseService::getNextOrderExercise($_SESSION['usuario_id'], $idCollection, $idExercise);
                if($orden=mysqli_fetch_assoc($result2)){
                    $orden = $orden['orden'];
                    $result3 = exerciseService::getNextExerciseToDo($_SESSION['usuario_id'], $idCollection, $orden);
                    if($nextEj=mysqli_fetch_assoc($result3)){
                        $nextEj = $nextEj['idEjercicio'];
                        $insert = exerciseService::insertNextExerciseToDo($_SESSION['usuario_id'], $nextEj);
                        echo 1;
                    }
                }
            }else{
                echo 1;
            }
        }
    }
    
     /**
     * Function that increases the number of attempts when an exercise starts.
     *
     * The number of attempts of an exercises increases one.
     * Echo 0 if error
     * Echo 1 if ok
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version  1.0
     *  @access public
     *  @return void
     */
    function initExercise(){
        $idExercise = $_POST['idExercise'];
        
        $result1 = exerciseService::updateNumberTries($idExercise, $_SESSION['usuario_id']);
        if($result1){
            echo 1;
        }else{
            echo 0;
        }
        
    }
    
?> 