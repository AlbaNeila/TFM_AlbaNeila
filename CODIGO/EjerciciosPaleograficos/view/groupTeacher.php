<?php
session_start();
if($_SESSION['usuario_tipo'] != "PROFESOR"){
    header('Location: ../view/login.php');
}
ob_start();
?>
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
           var request = $.ajax({
              type: "POST",
              url: "../controller/groupController.php",
              async: false,
              data: {
                method:"newGroup", grupo: $("#nombregrupo").val(), descripcion: $("#descripciongrupo").val()
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        flag= true;
                    }
                    else{
                        flag= false;
                        set_tooltip($("#nombregrupo"),"<?php echo(_("Ya existe un grupo con el mismo nombre. Por favor, introduzca un nombre de grupo diferente."));?>");
                    }
            });
        }       
        return flag;
    }
    
    function dialogue(content, title) {
        $('<div />').qtip({
            content: {
                text: content,
                title: title
            },
            position: {
                my: 'center', at: 'center',
                target: $(window)
            },
            show: {
                ready: true,
                modal: {
                    on: true,
                    blur: false
                }
            },
            hide: false,
            style: {classes: 'qtip-ubupaleodialog'
            },
            events: {
                render: function(event, api) {
                    $('button', api.elements.content).click(function(e) {
                        api.hide(e);
                    });
                },
                hide: function(event, api) { api.destroy(); }
            }
        });
    }
    
    function deleteGroup(){
        var rowId = mygrid.getSelectedId();
        var idGroup = mygrid.cellById(rowId, 0).getValue();
        
        var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar el grupo"));?>'}),
                      ok = $('<button />', {text: 'Ok', click: function() {deleteGroupTeacher(idGroup);}}),
                      cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});
                
        dialogue( message.add(ok).add(cancel), '<?php echo(_("Confirmación eliminar grupo"))?>'); 
    }
    
    function deleteGroupTeacher(idGroup){
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
                        mygrid.loadXML("../controller/gridControllers/gridGroups.php"); 
                    }
                    else{
                        alert("error");
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
        var modal = document.getElementById("openModal");
        document.getElementById("openModal").appendChild(input);
        mygrid2.clearAll();                                                        
        mygrid2.loadXML("../controller/gridControllers/gridAlerts.php?idGrupo="+idGroup); 
        window.location = $('#anchorOpenModal').attr('href'); 
    }
    
    function accessGroup(){
        var rowId = mygrid.getSelectedId();
        var idGroup = mygrid.cellById(rowId, 0).getValue();       
        var grupo = mygrid.cellById(rowId, 1).getValue();
        window.location.href = 'groupTeacherStudents.php?grupo='+grupo+'&idGrupo='+idGroup;
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
             var request = $.ajax({
              type: "POST",
              url: "../controller/groupController.php",
              async: false,
              data: {
                method:"acceptRequest", idGrupo: idGrupo, alumnos:JSON.stringify(alumnos) 
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        set_tooltip($("#gridRequests"),"<?php echo(_("Se ha otorgado acceso al grupo correctamente"));?>");
                        mygrid2.clearAll();
                        mygrid2.loadXML("../controller/gridControllers/gridAlerts.php?idGrupo="+idGrupo);
                        mygrid.clearAll();
                        mygrid.loadXML("../controller/gridControllers/gridGroups.php");  
                    }
                    else{
                        set_tooltip($("#gridRequests"),"<?php echo(_("Ocurrió un error"));?>");
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
             var request = $.ajax({
              type: "POST",
              url: "../controller/groupController.php",
              async: false,
              data: {
                method:"rejectRequest", idGrupo: idGrupo, alumnos:JSON.stringify(alumnos) 
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        set_tooltip($("#gridRequests"),"<?php echo(_("Se ha denegado acceso al grupo correctamente"));?>");
                        mygrid2.clearAll();
                        mygrid2.loadXML("../controller/gridControllers/gridAlerts.php?idGrupo="+idGrupo);
                        mygrid.clearAll();
                        mygrid.loadXML("../controller/gridControllers/gridGroups.php");  
                    }
                    else{
                        set_tooltip($("#gridRequests"),"<?php echo(_("Ocurrió un error"));?>");
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
include ('menu/menu2.php');
ob_start();
?>
    <div class="submenu">
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="groupTeacher.php" style="font-weight: bold"><?php echo(_("Grupos"));?></a></div>
        <div class="submenuitem2"><img src="../public/img/menu2.png"><a><?php echo(_("Alumnos"));?></a></div>
    </div>

        <div class="formulario" >
            <form  action="groupTeacher.php" method="post" onsubmit="return validateForm()">
                <h2><?php echo(_("Nuevo grupo"));?></h2>
                <table>
                    <tr>
                        <td class="td_label"><label><?php echo(_("Nombre"));?></label></td><td><input type="text" id="nombregrupo"></td>
                        <td class="td_label"><label><?php echo(_("Descripción"));?></label></td><td><input type="text" id="descripciongrupo" /></td>
                    </tr>
                    <tr>
                        <td><input  type="submit" name="newTeacher" value="<?php echo(_("Añadir"));?>" id="newTeacher" /></td>
                    </tr>
                </table>
            </form>
        </div>
        
        <div class="gridAfterForm" id="gridGroups" style="width: 85%; height: 85%;top:300px;"></div>
<div id="labelAux"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridGroups');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Código grupo"));?>, <?php echo(_("Nombre"));?>, <?php echo(_("Descripción"));?>, <?php echo(_("Nº alumnos"));?>, <?php echo(_("Solicitudes"));?>, <?php echo(_("Eliminar"));?>,<?php echo(_("Acceder"));?>");
            mygrid.setInitWidths("90,*,350,90,90,90,90");
            mygrid.setColAlign("center,left,left,center,center,center,center");
            mygrid.setColTypes("ro,ed,ed,ro,ro,ro,ro");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,200);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("false,true,false,false,false,false,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridGroups.php",onLoadFunction);  
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
        
        <a href="#openModal" id="anchorOpenModal"></a>
        <div id="openModal" class="modalDialog">
            <div>
            <a href="#close" id="closeModal" onclick="$('#idHidden').remove();" title="<?php echo(_("Cerrar"));?>" class="close">X</a>
            <h3><?php echo(_("Solicitud de acceso"));?></h3>
            <div id="gridRequests" style="width: 90%; height: 90%"></div>
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
            <input  type="submit" class="buttonModal" name="enviar" onclick="aceptarSolicitud()" value="<?php echo(_("Aceptar"));?>" id="aceptarSol"  />
            <input  type="submit" class="buttonModal" name="enviar" onclick="rechazarSolicitud()" value="<?php echo(_("Rechazar"));?>" id="rechazarSol" />
            <input  type="submit" class="buttonModal" name="enviar" onclick="posponerSolicitud()" value="<?php echo(_("Posponer"));?>" id="posponerSol" />
            </div>
        </div>
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>


