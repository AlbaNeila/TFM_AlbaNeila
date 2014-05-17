<?php
session_start();
ob_start();
?>
<script>
    function validateForm() {
       var u = check_empty($("#nombregrupo"));
        var p = check_empty($("#descripciongrupo"));
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
            style: {classes: 'qtip-blue'
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
    
    function deleteGroup(grupo){
        if(grupo!=""){
            var request = $.ajax({
              type: "POST",
              url: "../controller/groupController.php",
              async: false,
              data: {
                method:"deleteGroup", grupo: grupo
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        mygrid.clearAll();
                        mygrid.loadXML("../controller/gridControllers/gridGroups.php",addEventsToImages); 
                    }
                    else{
                        alert("error");
                    }
            });
        }
    }
    
    function addEventsToImages(){
    $(window).ready(function() { 
        setTimeout(function() {
            var td;
            var img;
            var grupo;
            $('.objbox tr').each(function (index){
                 $(this).children("td").each(function (index2) {
                    if(index2 == 4){ //Imagen alerta solicitud
                        $(this).children("img").bind('click',function($this){
                            var idfila = $(this).attr("id");
                            var idGrupo = mygrid.cells(idfila-1, 0).getValue();
                            //Agregamos un hidden con el id del grupo seleccionado
                            var input = document.createElement("input");
                            input.setAttribute("type", "hidden");                           
                            input.setAttribute("id", "idHidden");                            
                            input.setAttribute("value", idGrupo);
                            var modal = document.getElementById("openModal");
                            document.getElementById("openModal").appendChild(input);
                                                                       
                            mygrid2.loadXML("../controller/gridControllers/gridAlerts.php?idGrupo="+idGrupo); 
                            window.location = $('#anchorOpenModal').attr('href');                                          
                        });
                    }
                    if(index2 == 5){ //Imagen eliminar grupo 
                        $(this).children("img").bind('click',function($this){
                            var idfila = $(this).attr("id");
                            var grupo = mygrid.cells(idfila-1, 0).getValue();
                             var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar el grupo"));?>'}),
                              ok = $('<button />', {text: 'Ok', click: function() {deleteGroup(grupo);}}),
                              cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});
                        
                            dialogue( message.add(ok).add(cancel), '<?php echo(_("Confirmación eliminar grupo"))?>'); 
                        });
                    }
                });
            });
        },6000);
    });
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
                        mygrid.loadXML("../controller/gridControllers/gridGroups.php",addEventsToImages);  
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
        debugger;
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
                        mygrid.loadXML("../controller/gridControllers/gridGroups.php",addEventsToImages);  
                    }
                    else{
                        set_tooltip($("#gridRequests"),"<?php echo(_("Ocurrió un error"));?>");
                    }
            });
        }
    }
</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('/menu/menuGroupTeacher.php');
ob_start();
?>
        <div class="divForm" style="width:22%;min-width:278px;" action="groupTeacher.php" method="post" onsubmit="return validateForm()">
            <form>
                <h3><?php echo(_("Añadir nuevo grupo"));?></h3>
                <label><?php echo(_("Nombre"));?></label>
                <input type="text" id="nombregrupo">
                <label><?php echo(_("Descripción"));?></label>
                <input type="text" id="descripciongrupo" />
                <input  type="submit" name="newTeacher" value="<?php echo(_("Añadir"));?>" id="newTeacher" />
            </form>
        </div>
        <div id="gridGroups" style="width: 90%; height: 90%"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridGroups');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("Codigo grupo, Nombre, Descripción, Nº alumnos, Solicitudes, Eliminar");
            mygrid.setInitWidths("125,*,*,125,100,100");
            mygrid.setColAlign("left,left,left,left,center,center");
            mygrid.setColTypes("ro,ed,ed,ro,ro,ro");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,200);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("false,true,false,false,false,false");
            mygrid.setSizes();
            mygrid.setSkin("light");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridGroups.php",addEventsToImages);  
            mygrid.attachEvent("onEditCell", function(stage,rId,cInd,nValue,oValue){
                if (stage == 2){
                    var row = new Array();
                    var cont = 0;
                    var flag;
                    mygrid.forEachCell(rId,function(c){
                        row[cont]=c.getValue();
                        cont++;
                    });
                    row[1]=nValue;
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
            <a href="#close" id="closeModal" title="Close" class="close">X</a>
            <h3><?php echo(_("Solicitud de acceso"));?></h3>
            <div id="gridRequests" style="width: 90%; height: 90%"></div>
        <script>
            var mygrid2 = new dhtmlXGridObject('gridRequests');
            mygrid2.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid2.setHeader("Nombre, Apellidos, Email, Seleccionar");
            mygrid2.setInitWidths("*,*,*,100");
            mygrid2.setColAlign("left,left,left,center");
            mygrid2.setColTypes("ro,ro,ro,ch");
            mygrid2.enableSmartRendering(true);
            mygrid2.enableAutoHeight(true,200);
            mygrid2.enableAutoWidth(true);
            mygrid2.enableTooltips("true,true,true,false");
            mygrid2.setSizes();
            mygrid2.setSkin("light");
            mygrid2.init();
        </script>
            <input  type="submit" name="enviar" onclick="aceptarSolicitud()" value="<?php echo(_("Aceptar"));?>" id="aceptarSol"  />
            <input  type="submit" name="enviar" onclick="rechazarSolicitud()" value="<?php echo(_("Rechazar"));?>" id="rechazarSol" />
            <input  type="submit" name="enviar" onclick="window.location = $('#closeModal').attr('href');  " value="<?php echo(_("Posponer"));?>" id="posponerSol" />
            </div>
        </div>
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>


