<?php
include("../../model/grid_acceso_db.php");
class gridService{
    //SELECT QUERIES
    static function getCollectionsByUserAndGroup($idUser,$idGroup){
        return mysql_query("SELECT distinct coleccion.idColeccion, coleccion.nombre,coleccion.descripcion FROM usuario_grupo,grupo,grupo_coleccion,coleccion,usuario WHERE usuario.idUsuario=usuario_grupo.idUsuario and usuario_grupo.idGrupo=grupo.idGrupo and grupo.idGrupo=grupo_coleccion.idGrupo and grupo_coleccion.idColeccion=coleccion.idColeccion and usuario.idUsuario='".$idUser."' and grupo.idGrupo='".$idGroup."'");
    }
    
    static function getCollectionsAdmin(){
        return mysql_query("SELECT coleccion.idColeccion, coleccion.nombre,coleccion.descripcion FROM coleccion");
    }
    
    static function getCollectionsByStudent($idUser){
        return mysql_query("SELECT distinct coleccion.idColeccion, coleccion.nombre,coleccion.descripcion FROM usuario_grupo,grupo,grupo_coleccion,coleccion,usuario WHERE usuario.idUsuario=usuario_grupo.idUsuario and usuario_grupo.idGrupo=grupo.idGrupo and grupo.idGrupo=grupo_coleccion.idGrupo and grupo_coleccion.idColeccion=coleccion.idColeccion and usuario.idUsuario='".$idUser."'");
    }
    
    static function getCollectionsByDoc($idDoc){
        return mysql_query("SELECT coleccion.nombre FROM coleccion,coleccion_documento WHERE coleccion_documento.idDocumento = '".$idDoc."' AND coleccion_documento.idColeccion = coleccion.idColeccion");
    }
    
    static function getCollectionIdByDoc($idDoc){
        return mysql_query("SELECT coleccion_documento.idColeccion FROM coleccion_documento WHERE coleccion_documento.idDocumento='".$idDoc."'");
    }
    
    static function getCollections(){
        return mysql_query("SELECT coleccion.idColeccion,coleccion.nombre FROM coleccion");
    }
    
    static function getCountCollections($idCol){
        return mysql_query("SELECT count(*) as total FROM coleccion_documento WHERE coleccion_documento.idColeccion ='".$idCol."' ");
    }
    
    static function getCountExercises($idUser,$idCol){
        return mysql_query("SELECT count( distinct ejercicio.idEjercicio) as total FROM usuario,usuario_grupo,grupo,grupo_ejercicio_coleccion,ejercicio WHERE usuario.idUsuario='".$idUser."' and usuario.idUsuario=usuario_grupo.idUsuario and usuario_grupo.idGrupo=grupo.idGrupo and grupo.idGrupo=grupo_ejercicio_coleccion.idGrupo and grupo_ejercicio_coleccion.idColeccion='".$idCol."' and ejercicio.idEjercicio=grupo_ejercicio_coleccion.idEjercicio");
    }
    
    static function getAlertsStudents($idGroup){
        return mysql_query("SELECT usuario.idUsuario,usuario.nombre,usuario.apellidos,usuario.email FROM usuario,usuario_grupo WHERE usuario.idUsuario = usuario_grupo.idUsuario AND usuario_grupo.solicitud = 1 AND usuario_grupo.idGrupo = '".$idGroup."'");
    }
    
    static function getCollectionsTeacher($idUser){
        return mysql_query("SELECT DISTINCT coleccion.idColeccion, coleccion.nombre,coleccion.descripcion FROM grupo,grupo_coleccion,coleccion WHERE grupo.idUsuarioCreador = '".$idUser."' AND grupo.idGrupo=grupo_coleccion.idGrupo AND grupo_coleccion.idColeccion = coleccion.idColeccion");
    }
    
    static function getDocumentsNameById($idDoc){
        return mysql_query("SELECT documento.nombre FROM documento WHERE documento.idDocumento = '".$idDoc."'");
    }
    
    static function getDocumentTranscriptionById($idDoc){
        return mysql_query("SELECT documento.transcripcion FROM documento WHERE documento.idDocumento = '".$idDoc."'");
    }
    
    static function getDocumentsNumber($idCol){
        return mysql_query("SELECT count(*) as total FROM coleccion_documento WHERE coleccion_documento.idColeccion ='".$idCol."' ");
    }
    
    static function getStudentsNumber($idGroup){
        return mysql_query("SELECT count(*) as total FROM usuario_grupo WHERE usuario_grupo.idGrupo='".$idGroup."' AND usuario_grupo.solicitud=0");
    }
    
    static function getGroupsNumber($idUser){
        return mysql_query("SELECT count(*) as total FROM usuario_grupo WHERE usuario_grupo.idUsuario ='".$idUser."' AND usuario_grupo.solicitud='0' ");
    }
    
    static function getGroupsNumberOfCollection($idUser,$idCol){
        return mysql_query("SELECT count(*) as total FROM grupo,grupo_coleccion,coleccion WHERE grupo.idUsuarioCreador = '".$idUser."' AND grupo.idGrupo=grupo_coleccion.idGrupo AND grupo_coleccion.idColeccion = coleccion.idColeccion AND coleccion.idColeccion ='".$idCol."' ");
    }
    
    static function getGroupsNumberAdmin($idCol){
        return  mysql_query("SELECT count(*) as total FROM grupo_coleccion WHERE grupo_coleccion.idColeccion='".$idCol."' ");
    }
    
    static function getGroupsNumberOfTeacher($idUser){
        return mysql_query("SELECT count(*) as total FROM grupo WHERE grupo.idUsuarioCreador ='".$idUser."'");
    }
    
    /*
    static function getGroupsTeacher($idUser){
        return mysql_query("SELECT grupo.idGrupo,grupo.nombre FROM grupo WHERE grupo.idUsuarioCreador='".$idUser."'");
    }*/
    
    static function getTeacherOfCollection($idCol){
        return mysql_query("SELECT distinct usuario.nombre,usuario.apellidos FROM usuario,grupo,grupo_coleccion,coleccion WHERE usuario.idUsuario=grupo.idUsuarioCreador AND grupo.idGrupo=grupo_coleccion.idGrupo AND grupo_coleccion.idColeccion=coleccion.idColeccion AND coleccion.idColeccion='".$idCol."'");
    }
    
    static function getDocumentsOfCollection($idCol){
        return mysql_query("SELECT documento.idDocumento,documento.nombre, documento.descripcion,documento.tipoEscritura, documento.fecha FROM documento,coleccion_documento WHERE coleccion_documento.idColeccion = '".$idCol."' AND documento.idDocumento = coleccion_documento.idDocumento");
    }
    
    static function getDocumentsOfCollectionAdmin(){
        return mysql_query("SELECT documento.idDocumento,documento.nombre, documento.descripcion,documento.tipoEscritura, documento.fecha FROM documento");
    }
    
    static function getDocumentsOfStudent($idUser,$idCol){
        return mysql_query("SELECT distinct documento.idDocumento,documento.nombre, documento.descripcion,documento.tipoEscritura, documento.fecha FROM usuario,usuario_grupo,grupo,grupo_coleccion,coleccion,coleccion_documento,documento WHERE usuario.idUsuario='".$idUser."' and usuario.idUsuario=usuario_grupo.idUsuario and usuario_grupo.idGrupo=grupo.idGrupo and grupo.idGrupo IN (SELECT grupo.idGrupo FROM usuario_grupo,usuario,grupo WHERE usuario.idUsuario='".$idUser."' AND usuario_grupo.idUsuario=usuario.idUsuario AND usuario_grupo.idGrupo=grupo.idGrupo) and grupo.idGrupo=grupo_coleccion.idGrupo and grupo_coleccion.idColeccion=coleccion.idColeccion and coleccion.idColeccion=coleccion_documento.idColeccion and coleccion_documento.idDocumento=documento.idDocumento and coleccion.idColeccion='".$idCol."'");
    }
    
    static function getExercisesOfDocument($idDoc){
        return mysql_query("SELECT ejercicio.idEjercicio FROM ejercicio WHERE ejercicio.idDocumento = '".$idDoc."'");
    }
    
    static function getExercisesOfTeacher($idUser,$idCol){
        return mysql_query("select * from(SELECT distinct ejercicio.idEjercicio,ejercicio.nombre,ejercicio.idDocumento,ejercicio.idDificultad, ejercicio.tipo_objetivo, ejercicio.valor_objetivo,ejercicio.comprobarTranscripcion,grupo_ejercicio_coleccion.orden FROM ejercicio,grupo_ejercicio_coleccion,coleccion,usuario,grupo WHERE usuario.idUsuario='".$idUser."' AND usuario.idUsuario=grupo.idUsuarioCreador and coleccion.idColeccion='".$idCol."' and grupo_ejercicio_coleccion.idGrupo=grupo.idGrupo and grupo_ejercicio_coleccion.idColeccion=coleccion.idColeccion and ejercicio.idEjercicio=grupo_ejercicio_coleccion.idEjercicio order by grupo_ejercicio_coleccion.orden)AS tmp_table GROUP BY tmp_table.idEjercicio order by tmp_table.orden");
    }
    
    static function getExercisesOfAdmin($idCol){
        return mysql_query("select * from(SELECT ejercicio.idEjercicio,ejercicio.nombre,ejercicio.idDocumento,ejercicio.idDificultad, ejercicio.tipo_objetivo, ejercicio.valor_objetivo,ejercicio.comprobarTranscripcion,grupo_ejercicio_coleccion.orden FROM ejercicio,grupo_ejercicio_coleccion,coleccion WHERE coleccion.idColeccion='".$idCol."' and grupo_ejercicio_coleccion.idColeccion=coleccion.idColeccion and ejercicio.idEjercicio=grupo_ejercicio_coleccion.idEjercicio order by grupo_ejercicio_coleccion.orden) AS tmp_table GROUP BY tmp_table.idEjercicio order by tmp_table.orden");
    }
    
    static function getExercisesOfStudent($idUser,$idCol){
        return mysql_query("select * from(SELECT distinct ejercicio.idEjercicio,ejercicio.nombre,ejercicio.idDocumento,ejercicio.idDificultad, ejercicio.tipo_objetivo, ejercicio.valor_objetivo,ejercicio.comprobarTranscripcion,grupo_ejercicio_coleccion.orden FROM usuario,usuario_grupo,grupo,grupo_ejercicio_coleccion,ejercicio WHERE usuario.idUsuario='".$idUser."' and usuario.idUsuario=usuario_grupo.idUsuario and usuario_grupo.idGrupo=grupo.idGrupo and grupo.idGrupo=grupo_ejercicio_coleccion.idGrupo and grupo_ejercicio_coleccion.idColeccion='".$idCol."' and ejercicio.idEjercicio=grupo_ejercicio_coleccion.idEjercicio order by grupo_ejercicio_coleccion.orden) AS tmp_table GROUP BY tmp_table.idEjercicio order by tmp_table.orden");
    }
    
    static function getSuperado($idUser,$idEj){
        return mysql_query("SELECT usuario_ejercicio.superado FROM usuario_ejercicio WHERE usuario_ejercicio.idUsuario='".$idUser."' and usuario_ejercicio.idEjercicio='".$idEj."'");
    }
    
    static function getGroupsTeacher($idUser){
        return mysql_query("SELECT grupo.idGrupo,grupo.nombre,grupo.descripcion FROM grupo,usuario WHERE grupo.idUsuarioCreador=usuario.idUsuario AND usuario.idUsuario='".$idUser."'");
    }
    
    static function getGroupsAdmin(){
        return mysql_query("SELECT grupo.idGrupo,grupo.nombre,grupo.descripcion FROM grupo");
    }
    
    static function getGroupsStudent($idUser){
        return mysql_query("SELECT grupo.idGrupo,grupo.nombre,grupo.descripcion FROM grupo,usuario,usuario_grupo WHERE usuario.idUsuario=usuario_grupo.idUsuario AND usuario_grupo.idGrupo=grupo.idGrupo AND usuario_grupo.solicitud=0 and usuario.idUsuario='".$idUser."'");
    }
    
    static function getGroupsToRegister(){
        return mysql_query("SELECT grupo.nombre,usuario.nombre,usuario.idUsuario,grupo.idGrupo FROM grupo,usuario WHERE grupo.idUsuarioCreador=usuario.idUsuario AND usuario.tipo='PROFESOR'");
    }
    
    static function getNameSurnameGroupTeacher($idGroup){
        return mysql_query("SELECT usuario.nombre,usuario.apellidos FROM usuario,grupo WHERE grupo.idGrupo='".$idGroup."' AND usuario.idUsuario=grupo.idUsuarioCreador");
    }
    
    static function getGroupIdByUser($idUser){
        return mysql_query("SELECT usuario_grupo.idGrupo FROM usuario_grupo WHERE usuario_grupo.idUsuario='".$idUser."' AND usuario_grupo.solicitud=0");
    }
    
    static function getGroupsRequest($idGroup){
        return mysql_query("SELECT usuario_grupo.solicitud FROM usuario_grupo WHERE usuario_grupo.idGrupo='".$idGroup."' AND usuario_grupo.solicitud='1'");
    }
    
    static function getGroupByCollectionId($idCol){
        return mysql_query("SELECT grupo_coleccion.idGrupo FROM grupo_coleccion WHERE grupo_coleccion.idColeccion='".$idCol."'");
    }
    
    static function getGroupIdAndNameByCollectionId($idCol){
        return mysql_query("SELECT grupo.idGrupo,grupo.nombre FROM grupo,grupo_coleccion WHERE grupo.idGrupo=grupo_coleccion.idGrupo AND grupo_coleccion.idColeccion='".$idCol."'");
    }
    
    static function getGroupIdFromGroupExerciseCollection($idEj){
        return mysql_query("SELECT grupo_ejercicio_coleccion.idGrupo FROM grupo_ejercicio_coleccion WHERE grupo_ejercicio_coleccion.idEjercicio='".$idEj."'");
    }
    
    static function getStudents($idGroup){
        return mysql_query("SELECT usuario.nombre,usuario.apellidos,usuario.email,usuario.idUsuario FROM usuario,usuario_grupo WHERE usuario.idUsuario=usuario_grupo.idUsuario AND usuario_grupo.idGrupo='".$idGroup."' AND usuario_grupo.solicitud=0");
    }
    
    static function getTeachers(){
        return mysql_query("SELECT usuario.idUsuario,usuario.nombre,usuario.apellidos,usuario.email,usuario.usuario,usuario.password FROM usuario WHERE usuario.tipo='PROFESOR'");
    }
    
    static function getStudentsAdmin(){
        return mysql_query("SELECT usuario.idUsuario,usuario.nombre,usuario.apellidos,usuario.email,usuario.usuario,usuario.password FROM usuario WHERE usuario.tipo='ALUMNO'");
    }
    
    static function insertUsuarioEjercicioSuperado($idUser,$idEj){
        return mysql_query("INSERT INTO usuario_ejercicio (usuario_ejercicio.idUsuario, usuario_ejercicio.idEjercicio,usuario_ejercicio.superado) VALUES ('".$idUser."','".$idEj."','0')");
    }

}
?>