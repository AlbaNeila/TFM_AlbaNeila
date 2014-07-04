<?php
session_start();
if($_SESSION['usuario_tipo'] != "PROFESOR"){
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
    var selectedCollection="";
    var canUpdate = true;
    
    $(document).ready(function(){
       window.location = $('#closeModal').attr('href');  
       $('.dhx_combo_option_img').remove();
    });

    function validateForm() {
        var u = check_empty($("#nombreejercicio"),"<?php echo(_("Este campo es requerido"));?>");
        var p = check_empty($("#objetivo"),"<?php echo(_("Este campo es requerido"));?>");
        var flag = true;
        var grupos = combo6.getChecked();
        if(grupos.length == 0){
            set_tooltip($("#combo_grupo"),"<?php echo(_("Debe seleccionar al menos un grupo"));?>");
            flag=false;
        }
        
        var idDocumento = combo2.getSelectedValue();
        if(idDocumento==null){
            set_tooltip($("#combo_document"),"<?php echo(_("Debe seleccionar un documento a partir del que crear un ejercicio"));?>");
            flag=false;
        }
        
        if(u || p || !flag){
            flag= false;
        }
        else{
           var request = $.ajax({
              type: "POST",
              url: "../controller/exercisesController.php",
              async: false,
              data: {
                method:"newExercise",
                name: $("#nombreejercicio").val(),
                idDocument: idDocumento,
                idCollection:combo.getSelectedValue(),
                groups: JSON.stringify(grupos),
                dificult:combo3.getSelectedValue(),
                correction:combo5.getSelectedValue(),
                target:combo4.getSelectedValue(),
                targetnum:$("#objetivo").val()
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        flag= true;
                        set_tooltip_general_error("<?php echo(_("Se añadió el ejercicio correctamente."));?>"); 
                    }
                    if($.trim(request) == "0"){
                        flag= false;
                        set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                    }
                    if($.trim(request) == "2"){
                        flag=false;
                        set_tooltip($("#nombreejercicio"),"<?php echo(_("Ya existe un ejercicio con el mismo nombre. Por favor, introduzca un nombre de ejercicio diferente."));?>");
                    }
            });
        }       
        return flag;
    }

    
    function deleteEj(){
        var rowId = mygrid.getSelectedId();
        var idEj = mygrid.cellById(rowId, 0).getAttribute("idEj");
        var nameEj = mygrid.cellById(rowId, 1).getValue();

        var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar el ejercicio "));?>'+nameEj+'?'}),
                      ok = $('<button />', {text: 'Ok', click: function() {deleteEjAdmin(idEj);}}),
                      cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});                       
        dialogue( message.add(ok).add(cancel), '<?php echo(_("Confirmación eliminar ejercicio"))?>'); 
    }
    
    function deleteEjAdmin(idEj){
        if(idEj!=""){
            var request = $.ajax({
              type: "POST",
              url: "../controller/exercisesController.php",
              async: false,
              data: {
                method:"deleteExercise", idEj: idEj
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        mygrid.clearAll();
                        mygrid.loadXML("../controller/gridControllers/gridExercises.php?idCollection="+selectedCollection,onLoadFunction);
                        set_tooltip_general_error("<?php echo(_("Se eliminó el ejercicio correctamente."));?>"); 
                    }
                    else{
                        set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                    }
            });
        }
    }
    
    function updateTips(select){
        if(canUpdate){
           var rowId = mygrid.getSelectedId();
           var idEj = mygrid.cellById(rowId, 0).getAttribute("idEj");
           var value = select.options[select.selectedIndex].value;
           var request = $.ajax({
                  type: "POST",
                  url: "../controller/exercisesController.php",
                  async: false,
                  data: {
                    method:"updateTips", idEj: idEj, value:value
                  },
                  dataType: "script",   
                });
                request.success(function(request){
                        if($.trim(request) == "1"){
                            mygrid.clearAll();
                            mygrid.loadXML("../controller/gridControllers/gridExercises.php?idCollection="+selectedCollection,onLoadFunction);
                            set_tooltip_general("<?php echo(_("Se actualizó la dificultad del ejercicio correctamente."));?>"); 
                        }
                        else{
                            set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                        }
                }); 
        }else{
            set_tooltip($("#combo_selectcollection"),"<?php echo(_("La dificultad de los ejercicios no puede ser modificada porque estos se encuentran actualmente en uso."));?>");
        }
    }
    
    function updateTarget(select){
        if(canUpdate){
           var rowId = mygrid.getSelectedId();
           var idEj = mygrid.cellById(rowId, 0).getAttribute("idEj");
           var value = select.options[select.selectedIndex].value;
           var request = $.ajax({
                  type: "POST",
                  url: "../controller/exercisesController.php",
                  async: false,
                  data: {
                    method:"updateTarget", idEj: idEj, value:value
                  },
                  dataType: "script",   
                });
                request.success(function(request){
                        if($.trim(request) == "1"){
                            mygrid.clearAll();
                            mygrid.loadXML("../controller/gridControllers/gridExercises.php?idCollection="+selectedCollection,onLoadFunction);
                            set_tooltip_general("<?php echo(_("Se actualizó el objetivo del ejercicio correctamente."));?>"); 
                        }
                        else{
                            set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                        }
                }); 
        }else{
            set_tooltip($("#combo_selectcollection"),"<?php echo(_("El objetivo de los ejercicios no puede ser modificado porque estos se encuentran actualmente en uso."));?>");
        }
    }
    
    function updateCorrectionMode(select){
        if(canUpdate){
           var rowId = mygrid.getSelectedId();
           var idEj = mygrid.cellById(rowId, 0).getAttribute("idEj");
           var value = select.options[select.selectedIndex].value;
           var request = $.ajax({
                  type: "POST",
                  url: "../controller/exercisesController.php",
                  async: false,
                  data: {
                    method:"updateCorrectionMode", idEj: idEj, value:value
                  },
                  dataType: "script",   
                });
                request.success(function(request){
                        if($.trim(request) == "1"){
                            mygrid.clearAll();
                            mygrid.loadXML("../controller/gridControllers/gridExercises.php?idCollection="+selectedCollection,onLoadFunction);
                            set_tooltip_general("<?php echo(_("Se actualizó el modo de corrección del ejercicio correctamente."));?>"); 
                        }
                        else{
                            set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                        }
                }); 
        }else{
            set_tooltip($("#combo_selectcollection"),"<?php echo(_("El modo de corrección de los ejercicios no puede ser modificado porque estos se encuentran actualmente en uso."));?>");
        }
    }
    
    function consultGroups(){
        var rowId = mygrid.getSelectedId();
        var idEj = mygrid.cellById(rowId, 0).getValue();
        var idCol = mygrid.cellById(rowId, 0).getAttribute("idCol");

        $("#idEj").val(idEj);
        $("#idCol").val(idCol);
        $("#ejName").html(mygrid.cellById(rowId, 1).getValue());
        mygrid2.clearAll();
        mygrid2.loadXML("../controller/gridControllers/gridManageGroupsExercise.php?idSearched="+idEj+"&idCollection="+idCol);
        window.location = $('#anchorOpenModal').attr('href'); 
    }
    
    function saveGroupPermissions(){
        var groups = new Array();
        var permissions = new Array();
        var cont=0;
        var cont2=0;
        mygrid2.forEachRow(function(id){
             groups[cont] = mygrid2.cellById(id,0).getAttribute("idEj");
             cont++;
        });

         
         mygrid2.forEachRow(function(id){
               if(mygrid2.cellById(id,1).isChecked()){
                 permissions[cont2] = true;
               }else{
                  permissions[cont2] = false; 
               }
               cont2++;
        });
        if(permissions.indexOf(true)==-'1'){
            set_tooltip($("#gridGestionGrupos"),"<?php echo(_("Debe seleccionar al menos un grupo"));?>");
            flag = false;
        }
        else{
            var request = $.ajax({
                  type: "POST",
                  url: "../controller/exercisesController.php",
                  async: false,
                  data: {
                    method:"updatePermissionsGroup", groups: JSON.stringify(groups),permissions:JSON.stringify(permissions),idEj:$("#idEj").val(),idCol:$("#idCol").val()
                  },
                  dataType: "script",   
                });
                request.success(function(request){
                        if($.trim(request) == "1"){
                            window.location = $('#closeModal').attr('href');
                            set_tooltip_general("<?php echo(_("Se actualizaron los permisos correctamente."));?>"); 
                        }
                        else{
                            set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                        }
                });
        }
    }
    
    function cancelGroupPermissions(){
        window.location = $('#closeModal').attr('href'); 
    }
    
    var auxValue="";
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
        $('#gridExercises input').each(function (index){
            $(this).bind('focus',function(event){
               auxValue=$(this).val(); 
            });  
        });
        checkUpdate(); 
    }
    
    function checkUpdate(){
        canUpdate=true;
        var request = $.ajax({
                  type: "POST",
                  url: "../controller/exercisesController.php",
                  async: false,
                  data: {
                    method:"checkUpdateOrder", idCollection:selectedCollection
                  },
                  dataType: "script",   
                });
                request.success(function(request){
                        if($.trim(request) == "1"){  //No update order
                            canUpdate=false;
                        }
                });
    }
    
    function updateValueTarget(newValue){
        if(canUpdate){
           var idTarget = newValue.getAttribute("id");
           rowId=idTarget.split('target');
           var idEj = mygrid.cellById(rowId[1]-1, 0).getAttribute("idEj");
           
           
           var newVal= $('input#' + idTarget).val();
           
           if(newVal==""){
                $('input#' + idTarget).val(auxValue);
               set_tooltip($('input#' + idTarget),"<?php echo(_("Debe introducir un valor"));?>");
           }else{
               var request = $.ajax({
                      type: "POST",
                      url: "../controller/exercisesController.php",
                      async: false,
                      data: {
                        method:"updateValueTarget", idEj: idEj, value:newVal
                      },
                      dataType: "script",   
                    });
                    request.success(function(request){
                            if($.trim(request) == "1"){
                                mygrid.clearAll();
                                mygrid.loadXML("../controller/gridControllers/gridExercisesAdmin.php?idCollection="+selectedCollection,onLoadFunction);
                                set_tooltip_general("<?php echo(_("Se actualizó el objetivo del ejercicio correctamente."));?>"); 
                            }
                            else{
                                set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                            }
                    }); 
            }
        }else{
            var rowId = mygrid.getSelectedId();
            var idEj = mygrid.cellById(rowId, 0).getAttribute("idEj");
            var idTarget = newValue.getAttribute("id");
            $('input#' + idTarget).val(auxValue);

            set_tooltip($("#combo_selectcollection"),"<?php echo(_("El objetivo de los ejercicios no puede ser modificado porque estos se encuentran actualmente en uso."));?>");
        }
    }
    
    function upEj(){
        if(canUpdate){
            var numrows = mygrid.getRowsNum();
            var rowId = parseInt(mygrid.getSelectedId());
            var idEjUp = mygrid.cellById(rowId, 0).getValue();
            var orderUp = mygrid.cellById(rowId, 0).getAttribute("orden");
            sel=combo.getSelectedValue();
            
            if(numrows>0){ //Una fila
                if(rowId!=0){ //Primera fila
                   var idEjDown = mygrid.cellById(rowId-1, 0).getValue();
                   var orderDown = mygrid.cellById(rowId-1, 0).getAttribute("orden");
                    
                   var request = $.ajax({
                      type: "POST",
                      url: "../controller/exercisesController.php",
                      async: false,
                      data: {
                        method:"updateOrder", idEjUp: idEjUp,orderUp:orderUp, idEjDown:idEjDown, orderDown:orderDown
                      },
                      dataType: "script",   
                    });
                    request.success(function(request){
                        if($.trim(request) == "1"){                        
                            mygrid.clearAll();
                            mygrid.loadXML("../controller/gridControllers/gridExercises.php?idCollection="+selectedCollection,onLoadFunction);
                            set_tooltip_general("<?php echo(_("Se actualizó el orden del ejercicio correctamente."));?>"); 
                        }
                        else{
                            set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                        }
                    }); 
                }
            }
        }else{
                set_tooltip($("#combo_selectcollection"),"<?php echo(_("El orden de los ejercicios no puede ser modificado porque estos se encuentran actualmente en uso."));?>");
            }
    }
    
    function downEj(){
        if(canUpdate){
            var numrows = mygrid.getRowsNum();
            var rowId = parseInt(mygrid.getSelectedId());
            var idEjUp = mygrid.cellById(rowId, 0).getValue();
            var orderU = mygrid.cellById(rowId, 0).getAttribute("orden");
    
            if(numrows>0){ //Una fila
                if(numrows != (rowId+1)){ //Es la última fila
                   var idEjDown = mygrid.cellById(rowId+1, 0).getValue();
                   var orderD = mygrid.cellById(rowId+1, 0).getAttribute("orden");
                    
                   var request = $.ajax({
                      type: "POST",
                      url: "../controller/exercisesController.php",
                      async: false,
                      data: {
                        method:"updateOrder", idEjUp: idEjUp,orderUp:orderU, idEjDown:idEjDown, orderDown:orderD
                      },
                      dataType: "script",   
                    });
                    request.success(function(request){
                        if($.trim(request) == "1"){                        
                            mygrid.clearAll();
                            mygrid.loadXML("../controller/gridControllers/gridExercises.php?idCollection="+selectedCollection,onLoadFunction);
                            set_tooltip_general("<?php echo(_("Se actualizó el orden del ejercicio correctamente."));?>"); 
                        }
                        else{
                            set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                        }
                    }); 
                }
            }
        }else{
            set_tooltip($("#combo_selectcollection"),"<?php echo(_("El orden de los ejercicios no puede ser modificado porque estos se encuentran actualmente en uso."));?>");
        }
    }
    

</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('menu/menu3.php');
ob_start();
?>
        <div class="formulario"  style="left:35px;">
            <form action="exercisesTeacher.php" class="formsAdd" style="width:114% !important;"  method="post" onsubmit="return validateForm()">
                <h2><?php echo(_("Añadir nuevo ejercicio"));?></h2>
                <table>
                    <tr>
                        <td colspan="6"><p style="margin-bottom: 5px;"><?php echo(_("Seleccione un documento a partir del que crear un ejercicio:"));?></p></td>
                    </tr>
                    <tr>
                        <td class="td_label"><label><?php echo(_("Colección"));?></label></td><td><div id="combo_collection" style="width:200px; height:20px;"></div></td>
                        <td class="td_label"><label><?php echo(_("Documento"));?></label></td><td colspan="2"><div id="combo_document" style="width:200px; height:20px;"></div></td>
                        <td class="td_label"><label><?php echo(_("Grupo"));?></label></td><td><div id="combo_grupo" style="width:200px; height:20px;"></div></td>
                    </tr>
                    <tr><td><input type="text" value="" style="height: 15px;visibility:hidden;"></td></tr>
                    <tr>
                        <td class="td_label"><label><?php echo(_("Nombre"));?></label></td><td><input type="text" id="nombreejercicio" /></td>
                    </tr>
                    <tr>
                        <td class="td_label"><label><?php echo(_("Dificultad realización"));?></label></td><td><select style='width:200px;'  id="combo_pistas" name="alfa1">
                                                                                                                    <option value="0"><?php echo(_("Fácil"));?></option>
                                                                                                                    <option value="1"><?php echo(_("Medio"));?></option>
                                                                                                                    <option value="2"><?php echo(_("Difícil"));?></option>
                                                                                                                  </select></td>
                      <td class="td_label"><label><?php echo(_("Objetivo"));?></label></td><td><select style='width:200px;'  id="combo_objetivo" name="alfa1">
                                                                                                    <option value="0"><?php echo(_("% palabras acertadas"));?></option>
                                                                                                    <option value="1"><?php echo(_("Nº máximo de fallos"));?></option>
                                                                                                </select></td><td style="min-width:50px;"><input type="text" id="objetivo" size="4" style="width:50px;"/></td>
                    
                       <td class="td_label"><label><?php echo(_("Modo corrección"));?></label></td><td><select style='width:200px;'  id="combo_modo" name="alfa1">
                                                                                                            <option value="0"><?php echo(_("Corregir al final"));?></option>
                                                                                                            <option value="1"><?php echo(_("Corregir paso a paso"));?></option>
                                                                                                        </select></td>  
                    </tr>
                    <tr>
                        <td><input  type="submit" name="newExercise" value="<?php echo(_("Añadir"));?>" id="newExercise" /></td>
                    </tr>
                </table>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo = new dhtmlXCombo("combo_collection","comboCollection",200);
                    //dhtmlx.skin = 'dhx_skyblue';
                    combo.enableOptionAutoWidth(true);
                    combo.setOptionHeight(250);
                    combo.enableOptionAutoPositioning();
                    combo.loadXML("../controller/comboControllers/comboCollections.php");
                    combo.attachEvent("onChange", function(){
                        var selectedCollection = combo.getSelectedValue();
                        combo2.clearAll(true);
                        combo2.loadXML("../controller/comboControllers/comboDocuments.php?idCollection="+selectedCollection);
                        combo6.clearAll(true);
                        combo6.loadXML("../controller/comboControllers/comboGroups.php?method=adminExercises&idCollection="+selectedCollection);  
                    });  
                </script>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo2 = new dhtmlXCombo("combo_document","comboDocument",200);
                   // dhtmlx.skin = 'dhx_skyblue';
                    combo2.enableOptionAutoWidth(true);
                    combo2.enableOptionAutoHeight(true);
                    combo2.enableOptionAutoPositioning();
                </script>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo6 = new dhtmlXCombo("combo_grupo","comboGroups",200,'checkbox');
                    //dhtmlx.skin = 'dhx_skyblue';
                    combo6.enableOptionAutoWidth(true);
                    combo2.enableOptionAutoHeight(true);
                    combo6.enableOptionAutoPositioning();                    
                </script>
                <script>
                    var combo3=dhtmlXComboFromSelect("combo_pistas");
                    //dhtmlx.skin = 'dhx_skyblue';
                    combo3.enableOptionAutoWidth(true);
                    combo3.enableOptionAutoHeight(true);
                    combo3.enableOptionAutoPositioning();
                </script>
                <script>
                    var combo5=dhtmlXComboFromSelect("combo_modo");
                    //dhtmlx.skin = 'dhx_skyblue';
                    combo5.enableOptionAutoWidth(true);
                    combo5.enableOptionAutoHeight(true);
                    combo5.enableOptionAutoPositioning();
                </script>
                <script>
                    var combo4=dhtmlXComboFromSelect("combo_objetivo");
                    //dhtmlx.skin = 'dhx_skyblue';
                    combo4.enableOptionAutoWidth(true);
                    combo4.enableOptionAutoHeight(true);
                    combo4.enableOptionAutoPositioning();
                </script>
            </form>
        </div>
        
        <div class="formulario" style="top:405px;left:32px;" >
        <table>
            <tr><td><label class="labelModal"><?php echo(_("Seleccione una colección:"));?></label></td></tr>
             <tr><td><div id="combo_selectcollection" style="width:200px; height:20px;"></div></td></tr>         
        
        </table>
        </div>
        <script>
            window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
            var comboColeccion = new dhtmlXCombo("combo_selectcollection","comboCollection",200);
            dhtmlx.skin = 'dhx_skyblue';
            comboColeccion.enableOptionAutoWidth(true);
            comboColeccion.enableOptionAutoHeight(true);
            comboColeccion.enableOptionAutoPositioning();
            comboColeccion.loadXML("../controller/comboControllers/comboCollections.php");
            comboColeccion.attachEvent("onChange", function(){
               selectedCollection = comboColeccion.getSelectedValue(); 
               mygrid.clearAll()
               mygrid.loadXML("../controller/gridControllers/gridExercises.php?idCollection="+selectedCollection,onLoadFunction);
            });
        </script>
        
        
        <div class="gridAfterForm" id="gridExercises" style="width: 95%; height: 85%;top:480px;left:36px;"></div>
<div id="labelAux"></div>
        <script>
           var mygrid = new dhtmlXGridObject('gridExercises');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Código ejercicio"));?>, <?php echo(_("Ejercicio"));?>, <?php echo(_("Documento"));?>,  <?php echo(_("Dificultad realización"));?>, <?php echo(_("Objetivo"));?>,<?php echo(_("Modo corrección"));?>,<?php echo(_("Grupos"));?>,<?php echo(_("Ordenar"));?>,#cspan,<?php echo(_("Eliminar"));?>");
            mygrid.setInitWidths("90,*,*,90,210,170,90,40,40,90");
            mygrid.setColAlign("center,left,left,center,center,center,center,center,center,center");
            mygrid.setColTypes("ro,ed,ro,ro,ro,ro,ro,img,img,img,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,500);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("false,true,true,false,false,false,false,false,false,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                     

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
                          url: "../controller/exercisesController.php",
                          async: false,
                          data: {
                            method:"checkUpdateGrid", row:JSON.stringify(row),
                          }  
                        });
                        request.success(function(request){
                                if($.trim(request) == "1"){
                                    mygrid.cellById(rId, cInd).setValue(nValue); 
                                    mygrid.editStop();
                                    flag= true;
                                }
                                else{ 
                                    set_tooltip($('.cellSelected'),"<?php echo(_("Ya existe un ejercicio con el mismo nombre. Por favor, introduzca un nombre de ejercicio diferente."));?>");
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
                <a href="#close" id="closeModal" title="<?php echo(_("Cerrar"));?>" class="close">X</a>
                    <h3><?php echo(_("Acceso a grupos:"));?></h3>
                    <label class="labelModal"><?php echo(_("Ejercicio:"));?></label>
                    <label id="ejName"></label>
                    <p></p>
                    <input type="hidden" id="idEj" name="idEj"> 
                    <input type="hidden" id="idCol" name="idCol">                  
                    <div id="gridGestionGrupos" style="width: 100%; height: 100%"></div>
                    <script>
                        var mygrid2 = new dhtmlXGridObject('gridGestionGrupos');
                        mygrid2.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
                        mygrid2.setHeader("<?php echo(_("Grupo"));?>, <?php echo(_("Incluir ejercicio"));?>");
                        mygrid2.setInitWidths("*,*");
                        mygrid2.setColAlign("center,center");
                        mygrid2.setColTypes("ro,ch");
                        mygrid2.enableSmartRendering(true);
                        mygrid2.enableAutoHeight(true,200);
                        mygrid2.enableAutoWidth(true);
                        mygrid2.enableTooltips("true,false");
                        mygrid2.setSizes();
                        mygrid2.setSkin("dhx_skyblue");
                        mygrid2.init();
                    </script>
                    
                    <input  type="submit" class="buttonModal" name="enviar" onclick="saveGroupPermissions()" value="<?php echo(_("Guardar"));?>" id="aceptarGestionGrupos" />
                    <input  type="button" class="buttonModal" name="cancelar" onclick="cancelGroupPermissions()" value="<?php echo(_("Cancelar"));?>" id="cancelar" />
                    
            </div>
        </div> 
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>