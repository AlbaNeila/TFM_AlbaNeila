<?php
session_start();
if($_SESSION['usuario_tipo'] != "PROFESOR"){
    header('Location: ../view/login.php');
}
ob_start();
$grupo=$_REQUEST['grupo'];
$idGrupo=$_REQUEST['idGrupo'];
?>
<script>
    function deleteStudent(){
        var rowId = mygrid.getSelectedId();
        var idStudent = mygrid.cellById(rowId, 3).getAttribute("id");
        var nameUser = mygrid.cellById(rowId, 0).getValue();
        var surnameUser = mygrid.cellById(rowId, 1).getValue();
        
        var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea denegar el acceso a esta colección al alumno "));?>'+nameUser+' ' +surnameUser+'?'}),
                      ok = $('<button />', {text: 'Ok', click: function() {deleteStudentTeacher(idStudent);}}),
                      cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});
                
        dialogue( message.add(ok).add(cancel), '<?php echo(_("Confirmación eliminar alumno"))?>');
    }
    
    function deleteStudentTeacher(idStudent){
        if(idStudent!=""){
            var request = $.ajax({
              type: "POST",
              url: "../controller/groupController.php",
              async: false,
              data: {
                method:"deleteStudent", idAlumno: idStudent, idGrupo:<?php echo $idGrupo;?>,
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        mygrid.clearAll();
                        mygrid.loadXML("../controller/gridControllers/gridStudents.php?idGrupo="+<?php echo $idGrupo;?>); 
                        set_tooltip_general("<?php echo(_("Se denegó el acceso al grupo correctamente."));?>");   
                    }
                    else{
                        set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                    }
            });
        }
    }
    
    onLoadFunction = function onLoadFunction(){
        if(mygrid.getRowsNum()==0){
            var label = document.createElement("label");
            label.setAttribute("class", "gridAfterForm");                           
            label.setAttribute("id", "noRecords");
            label.setAttribute("style", "width: 85%; height: 90%;top:220px;text-align: center;");                            
            $(label).text("<?php echo(_("- No se encontraron resultados -"));?>");
            document.getElementById("labelAux").appendChild(label);
        }else{
           $("#noRecords").remove();
        }
    }
</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('menu/menu2.php');
ob_start();
?>
    <div class="submenu">
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="groupTeacher.php" ><?php echo(_("Grupos"));?></a></div>
        <div class="submenuitem2"><img src="../public/img/menu2.png"><a style="font-weight: bold"><?php echo(_("Alumnos"));?></a></div>
    </div>
    
    <div class="formulario">
        <h2><?php echo(_("GRUPO: "));?> <?php echo $grupo;?></h2>
    </div>
        
        
        <div class="gridAfterForm" id="gridGroups" style="width: 85%; height: 90%;top:180px;">           
        </div>
<div id="labelAux"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridGroups');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Nombre"));?>, <?php echo(_("Apellidos"));?>, <?php echo(_("Email"));?>, <?php echo(_("Denegar acceso"));?>");
            mygrid.setInitWidths("*,*,*,90");
            mygrid.setColAlign("left,left,left,center");
            mygrid.setColTypes("ro,ro,ro,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,200);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("true,true,true,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridStudents.php?idGrupo="+<?php echo $idGrupo;?>,onLoadFunction);


        </script>
        
        
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>


