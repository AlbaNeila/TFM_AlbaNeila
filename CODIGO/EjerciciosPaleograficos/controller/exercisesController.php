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
                 $insert = mysqli_query($GLOBALS['link'],"INSERT INTO ejercicio (ejercicio.nombre, ejercicio.comprobarTranscripcion,ejercicio.tipo_objetivo,ejercicio.valor_objetivo,ejercicio.idDocumento,ejercicio.idDificultad) VALUES ('".$name."','".$correction."','".utf8_decode($target)."','".$targetnum."','".$idDocument."','".$dificult."')");
                 if(!$insert){
                     $flag = 0;
                 }else{
                     //Insertar en la ternaria
                      $result2 = mysqli_query($GLOBALS['link'],"SELECT ejercicio.idEjercicio FROM ejercicio WHERE ejercicio.nombre= '".utf8_decode($name)."'");
                      $result2 = mysqli_fetch_assoc($result2);
                      $idEjercicio = $result2['idEjercicio'];
                      foreach($groups as $idGroup){
                        $max = mysqli_query($GLOBALS['link'],"select max(grupo_ejercicio_coleccion.orden) as max from ejercicio,grupo_ejercicio_coleccion where grupo_ejercicio_coleccion.idColeccion='".$idCollection."'");
                        $max = mysqli_fetch_assoc($max);
                        $order = $max['max']+1;
                        $insert2 = mysqli_query($GLOBALS['link'],"INSERT INTO grupo_ejercicio_coleccion (grupo_ejercicio_coleccion.idGrupo, grupo_ejercicio_coleccion.idEjercicio,grupo_ejercicio_coleccion.idColeccion,grupo_ejercicio_coleccion.orden) VALUES ('".$idGroup."','".$idEjercicio."','".$idCollection."','".$order."')");
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
    
    function updateTips(){
        $idEj = mysqli_real_escape_string($GLOBALS['link'],$_POST['idEj']);
        $value= mysqli_real_escape_string($GLOBALS['link'],$_POST['value']);
        
        $result = mysqli_query($GLOBALS['link'],"UPDATE ejercicio SET ejercicio.idDificultad='".$value."' WHERE ejercicio.idEjercicio='".$idEj."'");
        if($result!=FALSE){
                    echo 1; 
        }
        else{
            echo 0; //Error
        }
    }
    
    function updateTarget(){
        $idEj = mysqli_real_escape_string($GLOBALS['link'],$_POST['idEj']);
        $value= mysqli_real_escape_string($GLOBALS['link'],$_POST['value']);
        
        $result = mysqli_query($GLOBALS['link'],"UPDATE ejercicio SET ejercicio.valor_objetivo='".utf8_decode($value)."' WHERE ejercicio.idEjercicio='".$idEj."'");
        if($result!=FALSE){
                    echo 1; 
        }
        else{
            echo 0; //Error
        }
    }
    
    function updateValueTarget(){
        $idEj = mysqli_real_escape_string($GLOBALS['link'],$_POST['idEj']);
        $value= mysqli_real_escape_string($GLOBALS['link'],$_POST['value']);
        if($value==0){
            $value='% palabras acertadas';
        }else{
            $value=('nº máximo de fallos');
        }
        
        $result = mysqli_query($GLOBALS['link'],"UPDATE ejercicio SET ejercicio.tipo_objetivo='".utf8_decode($value)."' WHERE ejercicio.idEjercicio='".$idEj."'");
        if($result!=FALSE){
                    echo 1; //Delete grupo OK
        }
        else{
            echo 0; //Error
        }
    }
    
    function updateCorrectionMode(){
        $idEj = mysqli_real_escape_string($GLOBALS['link'],$_POST['idEj']);
        $value= mysqli_real_escape_string($GLOBALS['link'],$_POST['value']);
        
        $result = mysqli_query($GLOBALS['link'],"UPDATE ejercicio SET ejercicio.comprobarTranscripcion='".$value."' WHERE ejercicio.idEjercicio='".$idEj."'");
        if($result!=FALSE){
                    echo 1; //Delete grupo OK
        }
        else{
            echo 0; //Error
        }
    }
    
    function checkUpdateGrid(){
        $row = $_POST["row"];
        $row = json_decode("$row",true);
        
        $result = mysqli_query($GLOBALS['link'],"SELECT ejercicio.nombre FROM ejercicio WHERE ejercicio.nombre= '".utf8_decode($row[1])."' and ejercicio.idEjercicio<>'".$row[0]."'");
        
        if($result!=FALSE){
            if(!$fila=mysqli_fetch_assoc($result)) { //Si no hay filas es que no existe otro ejercicio con el mismo nombre, por lo que actualizamos la fila
                $result2 = mysqli_query($GLOBALS['link'],"UPDATE ejercicio SET ejercicio.nombre='".utf8_decode($row[1])."' WHERE ejercicio.idEjercicio='".$row[0]."'");
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
            $result = mysqli_query($GLOBALS['link'],"SELECT grupo_ejercicio_coleccion.idGrupo FROM grupo_ejercicio_coleccion WHERE grupo_ejercicio_coleccion.idGrupo= '".$group."' and grupo_ejercicio_coleccion.idEjercicio='".$idEj."' and grupo_ejercicio_coleccion.idColeccion='".$idCol."'");
            if($permissions[$cont]==true){
                if(!$fila=mysqli_fetch_assoc($result)){//Si no hay filas -> Insert
                     $insert = mysqli_query($GLOBALS['link'],"INSERT INTO grupo_ejercicio_coleccion (grupo_ejercicio_coleccion.idGrupo, grupo_ejercicio_coleccion.idColeccion,grupo_ejercicio_coleccion.idEjercicio) VALUES ('".$group."','".$idCol."','".$idEj."')");
                     if(!$insert){
                         $flag = 0;
                     }
                }
            }
            else{
                if($fila=mysqli_fetch_assoc($result)){//Si hay filas -> Delete
                    $delete = mysqli_query($GLOBALS['link'],"DELETE FROM grupo_ejercicio_coleccion WHERE grupo_ejercicio_coleccion.idGrupo= '".$group."' AND grupo_ejercicio_coleccion.idColeccion='".$idCol."' AND grupo_ejercicio_coleccion.idEjercicio='".$idEj."'");
                    if(!$delete){
                         $flag = 0;
                     }
                }
            }
            $cont++;
        }
        echo $flag;
    }

    function updateOrder(){
        $idEjUp = mysqli_real_escape_string($GLOBALS['link'],$_POST['idEjUp']);
        $orderUp= mysqli_real_escape_string($GLOBALS['link'],$_POST['orderUp']);

        $idEjDown = mysqli_real_escape_string($GLOBALS['link'],$_POST['idEjDown']);
        $orderDown= mysqli_real_escape_string($GLOBALS['link'],$_POST['orderDown']);
        
        $result1 = mysqli_query($GLOBALS['link'],"UPDATE grupo_ejercicio_coleccion SET grupo_ejercicio_coleccion.orden='".$orderDown."' WHERE grupo_ejercicio_coleccion.idEjercicio='".$idEjUp."'");
        $result2 = mysqli_query($GLOBALS['link'],"UPDATE grupo_ejercicio_coleccion SET grupo_ejercicio_coleccion.orden='".$orderUp."' WHERE grupo_ejercicio_coleccion.idEjercicio='".$idEjDown."'");
        if($result1 && $result2){
            echo 1;
        }else{
            echo 0;
        }
    }
    
    function checkUpdateOrder(){
        $idCollection=$_POST['idCollection'];
        $noUpdate=0;
        
        $result = mysqli_query($GLOBALS['link'],"SELECT distinct grupo_ejercicio_coleccion.idEjercicio FROM grupo_ejercicio_coleccion WHERE grupo_ejercicio_coleccion.idColeccion='".$idCollection."'");
        while($fila=mysqli_fetch_assoc($result)){
            $result2 = mysqli_query($GLOBALS['link'],"SELECT usuario_ejercicio.idEjercicio FROM usuario_ejercicio WHERE usuario_ejercicio.idEjercicio='".$fila['idEjercicio']."'");
            if($result2){
                if($row=mysqli_fetch_assoc($result2)){
                    $noUpdate=1;
                }
            }
        }
        echo $noUpdate;
    }
    
    function accessEj(){
        $idDocument = $_POST['idDocument'];
        $idExercise = $_POST['idExercise'];
        
        $result = mysqli_query($GLOBALS['link'],"SELECT documento.imagen,documento.nombre,documento.descripcion,documento.fecha,documento.tipoEscritura,documento.transcripcion FROM documento WHERE documento.idDocumento= '".$idDocument."'");
        $result2 = mysqli_query($GLOBALS['link'],"SELECT ejercicio.nombre,ejercicio.comprobarTranscripcion,ejercicio.tipo_objetivo,ejercicio.valor_objetivo,ejercicio.idDificultad FROM ejercicio WHERE ejercicio.idEjercicio= '".$idExercise."'");
        if($result!=FALSE){
                $row=mysqli_fetch_assoc($result);
                $imagen = utf8_encode($row['imagen']);
                $nombre = utf8_encode($row['nombre']);
                $descripcion = utf8_encode($row['descripcion']);
                $fecha = utf8_encode($row['fecha']);
                $tipoEscritura = utf8_encode($row['tipoEscritura']);
                $transcriptionFile = utf8_encode($row['transcripcion']);
                
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

    function finishEj(){
        $superado = $_POST['superado'];
        $idExercise = $_POST['idExercise'];
        $idCollection = $_POST['idCollection'];
        
        $result1 = mysqli_query($GLOBALS['link'],"UPDATE usuario_ejercicio SET usuario_ejercicio.superado='".$superado."',usuario_ejercicio.fecha=CURRENT_DATE,usuario_ejercicio.intentos=usuario_ejercicio.intentos+1 WHERE usuario_ejercicio.idEjercicio='".$idExercise."' AND usuario_ejercicio.idUsuario='".$_SESSION['usuario_id']."'");
        if($result1){
            if($superado==1){
                $result2 = mysqli_query($GLOBALS['link'],"SELECT distinct grupo_ejercicio_coleccion.orden FROM usuario,usuario_grupo,grupo,grupo_ejercicio_coleccion,ejercicio WHERE usuario.idUsuario='".$_SESSION['usuario_id']."' and usuario.idUsuario=usuario_grupo.idUsuario and usuario_grupo.idGrupo=grupo.idGrupo and grupo.idGrupo=grupo_ejercicio_coleccion.idGrupo and grupo_ejercicio_coleccion.idColeccion='".$idCollection."' and ejercicio.idEjercicio=grupo_ejercicio_coleccion.idEjercicio and grupo_ejercicio_coleccion.idEjercicio = '".$idExercise."' order by grupo_ejercicio_coleccion.orden");
                if($orden=mysqli_fetch_assoc($result2)){
                    $orden = $orden['orden'];
                    $result3 = mysqli_query($GLOBALS['link'],"SELECT distinct grupo_ejercicio_coleccion.idEjercicio FROM usuario,usuario_grupo,grupo,grupo_ejercicio_coleccion,ejercicio WHERE usuario.idUsuario='".$_SESSION['usuario_id']."' and usuario.idUsuario=usuario_grupo.idUsuario and usuario_grupo.idGrupo=grupo.idGrupo and grupo.idGrupo=grupo_ejercicio_coleccion.idGrupo and grupo_ejercicio_coleccion.idColeccion='".$idCollection."' and ejercicio.idEjercicio=grupo_ejercicio_coleccion.idEjercicio and grupo_ejercicio_coleccion.orden>'".$orden."' order by grupo_ejercicio_coleccion.orden");
                    if($nextEj=mysqli_fetch_assoc($result3)){
                        $nextEj = $nextEj['idEjercicio'];
                        $insert = mysqli_query($GLOBALS['link'],"INSERT INTO usuario_ejercicio (usuario_ejercicio.idUsuario, usuario_ejercicio.idEjercicio) VALUES ('".$_SESSION['usuario_id']."','".$nextEj."')");
                        echo 1;
                    }
                }
            }else{
                echo 1;
            }
        }
    }
    
?> 