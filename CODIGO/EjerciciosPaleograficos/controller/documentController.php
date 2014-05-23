 <?php
    session_start();
    include('../model/acceso_db.php');
    $method = $_POST['method'];
    
    switch($method){
        case 'deleteDoc':
            deleteDoc();
            break;
    }
    
    function deleteDoc(){
        $idDoc = mysqli_real_escape_string($GLOBALS['link'],$_POST['idDoc']);
        
        $result2 = mysqli_query($GLOBALS['link'],"SELECT documento.imagen,documento.transcripcion FROM documento WHERE documento.idDocumento= '".$idDoc."'");
        if($result2!=FALSE){
            $row=mysqli_fetch_assoc($result2);
            $imagen = $row['imagen'];
            $transcripcion = $row['transcripcion'];
            unlink($imagen);
            unlink($transcripcion);
            $result = mysqli_query($GLOBALS['link'],"DELETE FROM documento WHERE documento.idDocumento= '".$idDoc."'");
            if($result!=FALSE){
                 echo 1; //Delete document OK
            }
        }
        else{
            echo 0; //Error
        }

    }
?> 