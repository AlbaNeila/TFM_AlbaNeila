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
    
    function deleteGroup(){

    }
    
    function addEventsToImages(){
        setTimeout(function() {
            var td;
            var img;
            var grupo;
            $('.objbox tr').each(function (index){
                 $(this).children("td").each(function (index2) {
                    if(index2 == 4){ //Imagen alerta solicitud
                        $(this).children("img").bind('click',function($this){
                            alert("click en alerta solicitud");
                            window.location = $('#anchorOpenModal').attr('href');                                          
                        });
                    }
                    if(index2 == 5){ //Imagen eliminar grupo 
                        $(this).children("img").bind('click',function($this){
                            var idfila = $(this).attr("id");
                            var idGrupo = $('.objbox tr').eq(idfila);
                             var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar el grupo"));?>'}),
                              ok = $('<button />', {text: 'Ok', click: function() {deleteGroup();}}),
                              cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});
                        
                            dialogue( message.add(ok).add(cancel), '<?php echo(_("Confirmación eliminar grupo"))?>'); 
                        });
                    }
                });
            });
        },3000);
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
            mygrid.loadXML("../controller/gridGroups.php",addEventsToImages);  
            mygrid.attachEvent("onEditCell", function(stage,rId,cInd,nValue,oValue){
                if (stage == 2){
                    var row = new Array();
                    var cont = 0;
                    mygrid.forEachCell(rId,function(c){
                        row[cont]=c.getValue();
                        cont++;
                    });
                    
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
                                }
                                else{
                                    set_tooltip($('.cellSelected'),"<?php echo(_("Ya existe un grupo con el mismo nombre. Por favor, introduzca un nombre de grupo diferente."));?>");
                                    mygrid.cells(rId,cInd).setValue(oValue);
                                    mygrid.editStop(true);
                                    return false;
                                }
                        });
                    }
                    return true;
                }
            });
        </script>
        
        <a href="#openModal" id="anchorOpenModal"></a>
        <div id="openModal" class="modalDialog">
            <div>
            <a href="#close" title="Close" class="close">X</a>
            <h3><?php echo(_("Solicitud de acceso"));?></h3>
            <div id="gridRequests" style="width: 90%; height: 90%"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridRequests');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("Nombre, Apellidos, Email, Seleccionar");
            mygrid.setInitWidths("*,*,*,100");
            mygrid.setColAlign("left,left,left,center");
            mygrid.setColTypes("ro,ro,ro,ch");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,200);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("true,true,true,false");
            mygrid.setSizes();
            mygrid.setSkin("light");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridGroups.php");  
        </script>
                    <input  type="submit" name="enviar" value="<?php echo(_("Aceptar"));?>" id="aceptarSol" />
            <input  type="submit" name="enviar" value="<?php echo(_("Rechazar"));?>" id="rechazarSol" />
            <input  type="submit" name="enviar" value="<?php echo(_("Posponer"));?>" id="posponerSol" />
            </div>
        </div>
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>


