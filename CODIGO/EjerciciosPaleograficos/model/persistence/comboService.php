<?php
include("../../model/persistence/grid_acceso_db.php");
/**
* comboService class.
* 
* This class is used to access to get the information of the data base to load the DHTMLXCombos.
*
* @package  model/persistence
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
*/
class comboService{
    //SELECT QUERIES
   static function getCollectionsOfTeacher($idUser){
       return mysql_query("SELECT DISTINCT coleccion.nombre,coleccion.idColeccion FROM grupo,grupo_coleccion,coleccion WHERE grupo.idUsuarioCreador = '".$idUser."' AND grupo.idGrupo=grupo_coleccion.idGrupo AND grupo_coleccion.idColeccion = coleccion.idColeccion");
   }
   
   static function getCollectionsOfAdmin(){
       return mysql_query("SELECT DISTINCT coleccion.nombre,coleccion.idColeccion FROM coleccion");
   }
   
   static function getDocumentsOfCollection($idCol){
       return mysql_query("SELECT documento.nombre,documento.idDocumento FROM documento,coleccion_documento WHERE coleccion_documento.idColeccion = '".$idCol."' AND documento.idDocumento = coleccion_documento.idDocumento");
   }
   
   static function getGroupsOfAdmin(){
       return mysql_query("SELECT grupo.nombre,grupo.idGrupo FROM grupo");
   }
   
   static function getGroupsOfCollection($idCol){
       return mysql_query("SELECT grupo.nombre,grupo.idGrupo FROM grupo,grupo_coleccion WHERE grupo.idGrupo=grupo_coleccion.idGrupo AND grupo_coleccion.idColeccion='".$idCol."'");
   }
   
   static function getGroupsOfTeacher($idUser){
       return mysql_query("SELECT grupo.nombre,grupo.idGrupo FROM grupo WHERE grupo.idUsuarioCreador = '".$idUser."'");
   }
   
   static function getTeachers(){
       return mysql_query("SELECT usuario.nombre,usuario.apellidos,usuario.idUsuario FROM usuario WHERE usuario.tipo='PROFESOR'");
   }

}
?>