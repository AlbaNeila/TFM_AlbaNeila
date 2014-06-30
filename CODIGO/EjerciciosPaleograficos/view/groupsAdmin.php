<?php
session_start();
if($_SESSION['usuario_tipo'] != "ADMIN"){
    header('Location: ../view/login.php');
}
ob_start();
?>
<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxCombo/codebase/dhtmlxcombo.css">
<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxCombo/codebase/dhtmlx_custom.css">  
<script src="../lib/dhtmlxCombo/codebase/dhtmlxcommon.js"></script>
<script src="../lib/dhtmlxCombo/codebase/dhtmlxcombo.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_whp.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_extra.js"></script>
<script>
    $(document).ready(function(){
        window.location = $('#closeModal').attr('href'); 
    });

    function validateForm() {
        var u = check_empty($("#nombregrupo"),"<?php echo(_("Este campo es requerido"));?>");
        var p = check_empty($("#descripciongrupo"),"<?php echo(_("Este campo es requerido"));?>");
        var flag = false;

        if(u || p){
            flag= false;
        }
        else{
           var profesor =  combo.getSelectedValue(); 
           var request = $.ajax({
              type: "POST",
              url: "../controller/groupController.php",
              async: false,
              data: {
                method:"newGroupAdmin", grupo: $("#nombregrupo").val(), descripcion: $("#descripciongrupo").val(),profesor:profesor
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        flag= true;
                    }
                    if($.trim(request) == "0"){
                        flag= false;
                        set_tooltip($("#nombregrupo"),"<?php echo(_("Ya existe un grupo con el mismo nombre. Por favor, introduzca un nombre de grupo diferente."));?>");
                    }
                    if($.trim(request) == "2"){
                        flag=false;
                        set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                    }
            });
        }       
        return flag;
    }
    
    function consultGroups(){
        var rowId = mygrid.getSelectedId();
        var idTeacher = mygrid.cellById(rowId, 0).getValue();
        $("#idTeacher").val(idTeacher);
        $("#teacherName").html(mygrid.cellById(rowId, 1).getValue());
        mygrid2.clearAll();
        mygrid2.loadXML("../controller/gridControllers/gridTeacherGroups.php?idSearched="+idTeacher);
        window.location = $('#anchorOpenModal').attr('href'); 
    }
    
    function deleteGroup(){
        var rowId = mygrid.getSelectedId();
        var idGroup = mygrid.cellById(rowId, 0).getValue();
        var nameGroup = mygrid.cellById(rowId, 1).getValue();

        var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar el grupo "));?>'+nameGroup+'?'}),
                      ok = $('<button />', {text: 'Ok', click: function() {deleteGroupAdmin(idGroup);}}),
                      cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});                       
        dialogue( message.add(ok).add(cancel), '<?php echo(_("Confirmación eliminar grupo"))?>'); 
    }
    
    function deleteGroupAdmin(idGroup){
        if(idGroup!=""){
            var request = $.ajax({
              type: "POST",
              url: "../controller/groupController.php",
              async: false,
              data: {
                method:"deleteGroup", grupo: idGroup
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        mygrid.clearAll();
                        mygrid.loadXML("../controller/gridControllers/gridGroupsAdmin.php",onLoadFunction);
                        set_tooltip_general("<?php echo(_("Se eliminó el grupo correctamente."));?>"); 
                    }
                    else{
                        set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                    }
            });
        }
    }
    
    function showAlert(){
        var rowId = mygrid.getSelectedId();
        var idGroup = mygrid.cellById(rowId, 0).getValue();
        //Agregamos un hidden con el id del grupo seleccionado
        var input = document.createElement("input");
        input.setAttribute("type", "hidden");                           
        input.setAttribute("id", "idHidden");                            
        input.setAttribute("value", idGroup);
        $("#groupName").html(mygrid.cellById(rowId, 1).getValue());
        var modal = document.getElementById("openModal");
        document.getElementById("openModal").appendChild(input);
                                                   
        mygrid2.clearAll();                                                   
        mygrid2.loadXML("../controller/gridControllers/gridAlerts.php?idGrupo="+idGroup); 
        window.location = $('#anchorOpenModal').attr('href');        
    }
    
    function aceptarSolicitud(){
        var grupos = new Array();
        var alumnos = new Array();
        var cont = 0;

        mygrid2.forEachRow(function(id){
            mygrid2.forEachCell(id,function(c){
               if(c.isChecked()){
                 alumnos[cont] = mygrid2.cellById(id,0).getAttribute("id");
                 cont++;
               }
            });
        });
        if(alumnos.length == 0){
            set_tooltip($("#gridRequests"),"<?php echo(_("Debe seleccionar al menos un alumno"));?>");
            flag = false;
        }
        else{
            var idGrupo = $("#idHidden").val();
            var nameGroup = $("#groupName").val();
             var request = $.ajax({
              type: "POST",
              url: "../controller/groupController.php",
              async: false,
              data: {
                method:"acceptRequest", idGrupo: idGrupo,nameGroup:nameGroup, alumnos:JSON.stringify(alumnos) 
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        set_tooltip($("#gridRequests"),"<?php echo(_("Se ha otorgado acceso al grupo correctamente"));?>");
                        mygrid2.clearAll();
                        mygrid2.loadXML("../controller/gridControllers/gridAlerts.php?idGrupo="+idGrupo);
                        mygrid.updateFromXML("../controller/gridControllers/gridGroupsAdmin.php",true,true);  
                    }
                    else{
                        set_tooltip($("#gridRequests"),"<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>");
                    }
            });
        }
    }
    
    function rechazarSolicitud(){
        var grupos = new Array();
        var alumnos = new Array();
        var cont = 0;
        mygrid2.forEachRow(function(id){
            mygrid2.forEachCell(id,function(c){
               if(c.isChecked()){
                 alumnos[cont] = mygrid2.cellById(id,0).getAttribute("id");
                 cont++;
               }
            });
        });
        if(alumnos.length == 0){
            set_tooltip($("#gridRequests"),"<?php echo(_("Debe seleccionar al menos un alumno"));?>");
            flag = false;
        }
        else{
            var idGrupo = $("#idHidden").val();
            var nameGroup = $("#groupName").val();
             var request = $.ajax({
              type: "POST",
              url: "../controller/groupController.php",
              async: false,
              data: {
                method:"rejectRequest", idGrupo: idGrupo,nameGroup:nameGroup, alumnos:JSON.stringify(alumnos) 
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        set_tooltip($("#gridRequests"),"<?php echo(_("Se ha denegado acceso al grupo correctamente"));?>");
                        mygrid2.clearAll();
                        mygrid2.loadXML("../controller/gridControllers/gridAlerts.php?idGrupo="+idGrupo);
                        mygrid.clearAll();
                        mygrid.loadXML("../controller/gridControllers/gridGroupsAdmin.php");  
                    }
                    else{
                        set_tooltip($("#gridRequests"),"<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>");
                    }
            });
        }
    }
    
    function posponerSolicitud(){
        $("#idHidden").remove();
        window.location = $('#closeModal').attr('href'); 
    }
    
    onLoadFunction = function onLoadFunction(){
        if(mygrid.getRowsNum()==0){
            var label = document.createElement("label");
            label.setAttribute("class", "gridAfterForm");                           
            label.setAttribute("id", "noRecords");
            label.setAttribute("style", "width: 85%; height: 90%;top:400px;text-align: center;");                            
            $(label).text("<?php echo(_("- No se encontraron resultados -"));?>");
            document.getElementById("labelAux").appendChild(label);
        }else{
           $("#noRecords").remove();
        }
    }
    
</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('menu/menu1.php');
ob_start();
?>
    <div class="submenu">
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="usersAdmin.php" ><?php echo(_("Alumnos"));?></a></div>
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="teachersAdmin.php" ><?php echo(_("Profesores"));?></a></div>
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="groupsAdmin.php"  style="font-weight: bold"><?php echo(_("Grupos"));?></a></div>
    </div>

    <div class="formulario"  >
        <form id="formGroups" action="groupsAdmin.php" method="post" onsubmit="return validateForm()">
          <h2><?php echo(_("Nuevo grupo"));?></h2>
          <table>
              <tr>
                  <td class="td_label"><label><?php echo(_("Nombre"));?></label></td><td><input type="text" id="nombregrupo"></td>
                  <td class="td_label"><label><?php echo(_("Descripción"));?></label></td><td><input type="text" id="descripciongrupo" /></td>
              </tr>
              <tr>
                  <td class="td_label"><label><?php echo(_("Profesor"));?></label></td><td><div id="combo_zone" style="width:200px; height:20px;"></div></td>
              </tr>
              <tr>
                  <td><input  type="submit" name="newGroup" value="<?php echo(_("Crear"));?>" id="newGroup" /></td>
              </tr>
          </table>
          <script>
                window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                var combo = new dhtmlXCombo("combo_zone","comboGroups",200);
                dhtmlx.skin = 'dhx_skyblue';
                combo.enableOptionAutoWidth(true);
                combo.enableOptionAutoHeight(true);
                combo.enableOptionAutoPositioning();
                combo.loadXML("../controller/comboControllers/comboTeachers.php?method=admin"); 
           </script>
        </form>
    </div> 
    
    <div class="gridAfterForm" id="gridGroups" style="width: 85%; height: 85%;top:350px;"></div>
    <div id="labelAux"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridGroups');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Código grupo"));?>, <?php echo(_("Nombre"));?>, <?php echo(_("Descripción"));?>,<?php echo(_("Profesor"));?>, <?php echo(_("Nº alumnos"));?>, <?php echo(_("Solicitudes"));?>, <?php echo(_("Eliminar"));?>");
            mygrid.setInitWidths("100,*,*,*,100,100,100");
            mygrid.setColAlign("center,left,left,left,center,center,center");
            mygrid.setColTypes("ro,ed,ed,ro,ro,ro,ro");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,400);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("false,true,true,true,false,false,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridGroupsAdmin.php",onLoadFunction);
            mygrid.attachEvent("onEditCell", function(stage,rId,cInd,nValue,oValue){
                if (stage == 2){
                    var row = new Array();
                    var cont = 0;
                    var flag;
                    mygrid.forEachCell(rId,function(c){
                        row[cont]=c.getValue();
                        cont++;
                    });
                    //row[1]=nValue;
                    if(nValue == ""){
                        set_tooltip($('.cellSelected'),"<?php echo(_("No puede estar vacío."));?>");
                        return false;
                    }
                    else{
                        var request = $.ajax({
                          type: "POST",
                          url: "../controller/groupController.php",
                          async: false,
                          data: {
                            method:"checkUpdateGrid", row:JSON.stringify(row) 
                          }  
                        });
                        request.success(function(request){
                                if($.trim(request) == "1"){
                                    mygrid.cellById(rId, cInd).setValue(nValue); 
                                    mygrid.editStop();
                                    flag= true;
                                }
                                else{ 
                                    set_tooltip($('.cellSelected'),"<?php echo(_("Ya existe un grupo con el mismo nombre. Por favor, introduzca un nombre de grupo diferente."));?>");
                                    mygrid.cells(rId,cInd).setValue(oValue);
                                    mygrid.editStop(true);
                                    flag= false;
                                }
                        });
                    }
                    return flag;
                }
            });  
        </script>
    </div>   
    
   <a href="#openModal" id="anchorOpenModal"></a>
        <div id="openModal" class="modalDialog">
            <div style="width: 450px;">
            <a href="#close" id="closeModal" onclick="$('#idHidden').remove();" title="<?php echo(_("Cerrar"));?>" class="close">X</a>
            <h3><?php echo(_("Solicitud de acceso"));?></h3>
            <label class="labelModal"><?php echo(_("Grupo:"));?></label>
            <label id="groupName"></label>
            <p></p>
            <div id="gridRequests" style="width: 100%; height: 100%"></div>
        <script>
            var mygrid2 = new dhtmlXGridObject('gridRequests');
            mygrid2.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid2.setHeader("<?php echo(_("Nombre"));?>, <?php echo(_("Apellidos"));?>, <?php echo(_("Email"));?>, <?php echo(_("Seleccionar"));?>");
            mygrid2.setInitWidths("*,*,*,100");
            mygrid2.setColAlign("left,left,left,center");
            mygrid2.setColTypes("ro,ro,ro,ch");
            mygrid2.enableSmartRendering(true);
            mygrid2.enableAutoHeight(true,200);
            mygrid2.enableAutoWidth(true);
            mygrid2.enableTooltips("true,true,true,false");
            mygrid2.setSizes();
            mygrid2.setSkin("dhx_skyblue");
            mygrid2.init();
        </script>
            <p></p>
            <input  type="submit" class="buttonModal" name="enviar" onclick="aceptarSolicitud()" value="<?php echo(_("Aceptar"));?>" id="aceptarSol"  />
            <input  type="submit" class="buttonModal" name="enviar" onclick="rechazarSolicitud()" value="<?php echo(_("Rechazar"));?>" id="rechazarSol" />
            <input  type="submit" class="buttonModal" name="enviar" onclick="posponerSolicitud() " value="<?php echo(_("Posponer"));?>" id="posponerSol" />
            </div>
        </div>
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>


