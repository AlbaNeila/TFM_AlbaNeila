<?php
session_start();
if($_SESSION['usuario_tipo'] != "ALUMNO"){
    header('Location: ../view/login.php');
}
ob_start();
?>
<script>
    function accessGroup(){
        var rowId = mygrid.getSelectedId();
        var idGroup = mygrid.cellById(rowId, 0).getValue();
        var group = mygrid.cellById(rowId, 1).getValue();
        
        $('#idGrupo').val(idGroup);
        $('#grupo').val(group);
        $('form#access').submit();       
    }
    
    function requestAccess(){
        var rowId = mygrid.getSelectedId();
        var idGroup = mygrid.cellById(rowId, 0).getValue();
        var nameGroup = mygrid.cellById(rowId, 1).getValue();
        var nameTeacher = mygrid.cellById(rowId, 3).getValue();
        
        var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea enviar una solicitud al profesor "));?>'+nameTeacher+'<?php echo(_(" de acceso a su grupo "));?>'+nameGroup+'?'}),
                      ok = $('<button />', {text: '<?php echo(_("Enviar"))?>', click: function() {acceptRequestAccess(idGroup)}}),
                      cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});
    
        dialogue( message.add(ok).add(cancel), '<?php echo(_("Solicitud de acceso a grupo"))?>'); 
    }
    
    function acceptRequestAccess(idGroup){
        var request = $.ajax({
              type: "POST",
              url: "../controller/groupController.php",
              async: false,
              data: {
                method:"requestAccess", idGroup: idGroup
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        $('qtip-0').parents('.qtip').qtip('hide');
                        mygrid.updateFromXML("../controller/gridControllers/gridGroupsStudent.php",onLoadFunction,true)
                        set_tooltip_general("<?php echo(_("La solicitud de grupo se envió correctamente."));?>"); 
                    }
                    if($.trim(request) == "0"){
                        set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                    }
            });
    }
    
    function requestSent(){
        var cell = $('td.cellselected');
        set_tooltip_left(cell,'<?php echo(_("Ya ha sido enviada una solicitud de acceso. Por favor, espere a que el profesor responda."))?>');
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
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="groupStudent.php" style="font-weight: bold"><?php echo(_("Grupos"));?></a></div>
        <div class="submenuitem2"><img src="../public/img/menu2.png"><a><?php echo(_("Colecciones"));?></a></div>
    </div>
        
        <div class="formulario">
            <h2><?php echo(_("Grupos disponibles"));?></h2>
        </div>
        <div class="gridAfterForm" id="gridGroups" style="width: 85%; height: 85%;top:180px"></div>
        <div id="labelAux"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridGroups');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Código grupo"));?>, <?php echo(_("Nombre"));?>, <?php echo(_("Descripción"));?>, <?php echo(_("Profesor responsable"));?>, <?php echo(_("Acceder"));?>");
            mygrid.setInitWidths("100,210,*,190,90");
            mygrid.setColAlign("center,left,left,center,center");
            mygrid.setColTypes("ro,ro,ro,ro,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true);
            mygrid.enableAutoWidth(true,1000);
            mygrid.enableTooltips("false,true,true,true,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridGroupsStudent.php",onLoadFunction);              
        </script>
        <form action="accessGroupStudent.php" name="access" id="access" method="post" style="display:none;">
            <input type="hidden" name="grupo" id="grupo" value=""/>
            <input type="hidden" name="idGrupo"  id="idGrupo" value=""/>            
        </form>
        
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>


