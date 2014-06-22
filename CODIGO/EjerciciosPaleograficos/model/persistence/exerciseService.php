<?php
include("../model/acceso_db.php");
class exerciseService{
    
    
    //SELECT QUERIES
    static function getByName($nameEj){
        return mysqli_query($GLOBALS['link'],"SELECT ejercicio.idEjercicio FROM ejercicio WHERE ejercicio.nombre= '".$nameEj."'");
    }
    
    static function checkNameNotRepeat($nameEj,$idEj){
        return mysqli_query($GLOBALS['link'],"SELECT ejercicio.nombre FROM ejercicio WHERE ejercicio.nombre= '".$nameEj."' and ejercicio.idEjercicio<>'".$idEj."'");
    }
    
    static function getById($idEj){
        return mysqli_query($GLOBALS['link'],"SELECT ejercicio.nombre,ejercicio.comprobarTranscripcion,ejercicio.tipo_objetivo,ejercicio.valor_objetivo,ejercicio.idDificultad FROM ejercicio WHERE ejercicio.idEjercicio= '".$idEj."'");
    }
    
    //INSERT QUERIES
    static function insertExercise($name,$correction,$target,$targetnum,$idDocument,$dificult){
        return mysqli_query($GLOBALS['link'],"INSERT INTO ejercicio (ejercicio.nombre, ejercicio.comprobarTranscripcion,ejercicio.tipo_objetivo,ejercicio.valor_objetivo,ejercicio.idDocumento,ejercicio.idDificultad) VALUES ('".$name."','".$correction."','".$target."','".$targetnum."','".$idDocument."','".$dificult."')");
    }
    
    //DELETE QUERIES
    static function deleteById($idEj){
        return mysqli_query($GLOBALS['link'],"DELETE FROM ejercicio WHERE ejercicio.idEjercicio= '".$idEj."'");
    }
    
    //UPDATE QUERIES
   static function updateTipsById($idDificult,$idEj){
       return mysqli_query($GLOBALS['link'],"UPDATE ejercicio SET ejercicio.idDificultad='".$idDificult."' WHERE ejercicio.idEjercicio='".$idEj."'");
   }
   
   static function updateTargetById($target,$idEj){
       return mysqli_query($GLOBALS['link'],"UPDATE ejercicio SET ejercicio.valor_objetivo='".$target."' WHERE ejercicio.idEjercicio='".$idEj."'");
   }
   
   static function updateValueTargetById($valueTarget,$idEj){
       return mysqli_query($GLOBALS['link'],"UPDATE ejercicio SET ejercicio.tipo_objetivo='".$valueTarget."' WHERE ejercicio.idEjercicio='".$idEj."'");
   }
   
   static function updateCorrectionModeById($mode,$idEj){
       return mysqli_query($GLOBALS['link'],"UPDATE ejercicio SET ejercicio.comprobarTranscripcion='".$mode."' WHERE ejercicio.idEjercicio='".$idEj."'");
   }
   
   static function updateNameById($nameEj,$idEj){
       return mysqli_query($GLOBALS['link'],"UPDATE ejercicio SET ejercicio.nombre='".$nameEj."' WHERE ejercicio.idEjercicio='".$idEj."'");
   }
   
   /*** GRUPO_EJERCICIO_COLECCION ***/
    
    //SELECT QUERIES
    static function getMaxOrder($idCol){
        return mysqli_query($GLOBALS['link'],"select max(grupo_ejercicio_coleccion.orden) as max from ejercicio,grupo_ejercicio_coleccion where grupo_ejercicio_coleccion.idColeccion='".$idCol."'");
    }
    
    static function getGrupoEjercicioColeccionByIds($idGroup,$idEj,$idCol){
        return mysqli_query($GLOBALS['link'],"SELECT grupo_ejercicio_coleccion.idGrupo FROM grupo_ejercicio_coleccion WHERE grupo_ejercicio_coleccion.idGrupo= '".$idGroup."' and grupo_ejercicio_coleccion.idEjercicio='".$idEj."' and grupo_ejercicio_coleccion.idColeccion='".$idCol."'");
    }
    
    static function getGrupoEjercicioColeccionByIdCol($idCol){
        return mysqli_query($GLOBALS['link'],"SELECT distinct grupo_ejercicio_coleccion.idEjercicio FROM grupo_ejercicio_coleccion WHERE grupo_ejercicio_coleccion.idColeccion='".$idCol."'");
    }
    
    static function getNextOrderExercise($idUser,$idCol,$idEj){
        return mysqli_query($GLOBALS['link'],"SELECT distinct grupo_ejercicio_coleccion.orden FROM usuario,usuario_grupo,grupo,grupo_ejercicio_coleccion,ejercicio WHERE usuario.idUsuario='".$idUser."' and usuario.idUsuario=usuario_grupo.idUsuario and usuario_grupo.idGrupo=grupo.idGrupo and grupo.idGrupo=grupo_ejercicio_coleccion.idGrupo and grupo_ejercicio_coleccion.idColeccion='".$idCol."' and ejercicio.idEjercicio=grupo_ejercicio_coleccion.idEjercicio and grupo_ejercicio_coleccion.idEjercicio = '".$idEj."' order by grupo_ejercicio_coleccion.orden");
    }
    
    static function getNextExerciseToDo($idUser,$idCol,$orden){
        return mysqli_query($GLOBALS['link'],"SELECT distinct grupo_ejercicio_coleccion.idEjercicio FROM usuario,usuario_grupo,grupo,grupo_ejercicio_coleccion,ejercicio WHERE usuario.idUsuario='".$idUser."' and usuario.idUsuario=usuario_grupo.idUsuario and usuario_grupo.idGrupo=grupo.idGrupo and grupo.idGrupo=grupo_ejercicio_coleccion.idGrupo and grupo_ejercicio_coleccion.idColeccion='".$idCol."' and ejercicio.idEjercicio=grupo_ejercicio_coleccion.idEjercicio and grupo_ejercicio_coleccion.orden>'".$orden."' order by grupo_ejercicio_coleccion.orden");
    }
    
    //INSERT QUERIES
   static function insertGrupoEjercicioColeccion($idGroup,$idEj,$idCol,$order){
       return mysqli_query($GLOBALS['link'],"INSERT INTO grupo_ejercicio_coleccion (grupo_ejercicio_coleccion.idGrupo, grupo_ejercicio_coleccion.idEjercicio,grupo_ejercicio_coleccion.idColeccion,grupo_ejercicio_coleccion.orden) VALUES ('".$idGroup."','".$idEj."','".$idCol."','".$order."')");
   }
   
   //DELETE QUERIES
    static function deleteGrupoEjercicioColeccionByIds($idGroup,$idCol,$idEj){
        return mysqli_query($GLOBALS['link'],"DELETE FROM grupo_ejercicio_coleccion WHERE grupo_ejercicio_coleccion.idGrupo= '".$idGroup."' AND grupo_ejercicio_coleccion.idColeccion='".$idCol."' AND grupo_ejercicio_coleccion.idEjercicio='".$idEj."'");
    }
    
    //UPDATE QUERIES
    static function updateOrderByIdEj($ordern,$idEj){
        return mysqli_query($GLOBALS['link'],"UPDATE grupo_ejercicio_coleccion SET grupo_ejercicio_coleccion.orden='".$order."' WHERE grupo_ejercicio_coleccion.idEjercicio='".$idEj."'");
    }
    
    
     /*** USUARIO_EJERCICIO ***/
    
    //SELECT QUERIES
    static function getUsuarioEjercicioByIdEj($idEj){
        return mysqli_query($GLOBALS['link'],"SELECT usuario_ejercicio.idEjercicio FROM usuario_ejercicio WHERE usuario_ejercicio.idEjercicio='".$idEj."'");
    }
    
    //UPDATE QUERIES
    static function updateUsuarioEjercicioSuperadoByIds($superado,$idEj,$idUser){
        return mysqli_query($GLOBALS['link'],"UPDATE usuario_ejercicio SET usuario_ejercicio.superado='".$superado."',usuario_ejercicio.fecha=CURRENT_DATE WHERE usuario_ejercicio.idEjercicio='".$idEj."' AND usuario_ejercicio.idUsuario='".$idUser."'");
    }
    
    static function updateNumberTries($idEj,$idUser){
        return mysqli_query($GLOBALS['link'],"UPDATE usuario_ejercicio SET usuario_ejercicio.fecha=CURRENT_DATE,usuario_ejercicio.intentos=usuario_ejercicio.intentos+1 WHERE usuario_ejercicio.idEjercicio='".$idEj."' AND usuario_ejercicio.idUsuario='".$idUser."'");
    }
    
    //INSERT QUERIES
    static function insertNextExerciseToDo($idUser,$idEj){
        return mysqli_query($GLOBALS['link'],"INSERT INTO usuario_ejercicio (usuario_ejercicio.idUsuario, usuario_ejercicio.idEjercicio) VALUES ('".$idUser."','".$idEj."')");
    }
}
?>