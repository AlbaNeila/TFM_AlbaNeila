<?php
session_start();
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
    
    function validateForm() {
        var u = check_empty($("#nombreejercicio"));
        var p = check_empty($("#objetivo"));
        var flag = true;
        var grupos = combo6.getChecked();
        if(grupos.length == 0){
            set_tooltip($("#combo_grupo"),"<?php echo(_("Debe seleccionar al menos un grupo"));?>");
            flag=false;
        }
        
        var idDocumento = combo2.getSelectedValue();
        if(idDocumento==null){
            set_tooltip($("#combo_grupo"),"<?php echo(_("Debe seleccionar un documento a partir del que crear un ejercicio"));?>");
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
                    }
                    if($.trim(request) == "0"){
                        flag= false;
                        alert("error");
                    }
                    if($.trim(request) == "2"){
                        flag=false;
                        set_tooltip($("#nombreejercicio"),"<?php echo(_("Ya existe un ejercicio con el mismo nombre. Por favor, introduzca un nombre de ejercicio diferente."));?>");
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
    
    function deleteEj(){
        var rowId = mygrid.getSelectedId();
        var idEj = mygrid.cellById(rowId, 0).getAttribute("idEj");

        var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar el ejercicio?"));?>'}),
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
                        mygrid.loadXML("../controller/gridControllers/gridExercisesAdmin.php");
                    }
                    else{
                        alert("error");
                    }
            });
        }
    }
    
    function updateTips(select){
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
                        mygrid.loadXML("../controller/gridControllers/gridExercisesAdmin.php");
                    }
                    else{
                        alert("error");
                    }
            }); 
    }
    
    function updateTarget(select){
       var rowId = mygrid.getSelectedId();
       var idEj = mygrid.cellById(rowId, 0).getAttribute("idEj");
       var value = select.options[select.selectedIndex].value;
       var request = $.ajax({
              type: "POST",
              url: "../controller/exercisesController.php",
              async: false,
              data: {
                method:"updateTarget", idEj: idEj, value:valu
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        mygrid.clearAll();
                        mygrid.loadXML("../controller/gridControllers/gridExercisesAdmin.php");
                    }
                    else{
                        alert("error");
                    }
            }); 
    }
    
    function updateCorrectionMode(select){
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
                        mygrid.loadXML("../controller/gridControllers/gridExercisesAdmin.php?idCollection="+selectedCollection,onLoadFunction);
                    }
                    else{
                        alert("error");
                    }
            }); 
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
                        }
                        else{
                            alert("error");
                        }
                });
        }
    }
    
    function cancelGroupPermissions(){
        window.location = $('#closeModal').attr('href'); 
    }
    
    
    var auxValue="";
    onLoadFunction = function onLoadFunction(){
        $('#gridExercises input').each(function (index){
            $(this).bind('focus',function(event){
               auxValue=$(this).val(); 
            });  
        });
    }
    
    function updateValueTarget(newValue){
       var rowId = mygrid.getSelectedId();
       var idEj = mygrid.cellById(rowId, 0).getAttribute("idEj");
       
       var idTarget = newValue.getAttribute("id");
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
                        }
                        else{
                            alert("error");
                        }
                }); 
        }
    }
    
    function upEj(){
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
                        mygrid.loadXML("../controller/gridControllers/gridExercisesAdmin.php?idCollection="+selectedCollection,onLoadFunction);
                    }
                    else{
                        alert("error");
                    }
                }); 
            }
        }
    }
    
    function downEj(){
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
                        mygrid.loadXML("../controller/gridControllers/gridExercisesAdmin.php?idCollection="+selectedCollection,onLoadFunction);
                    }
                    else{
                        alert("error");
                    }
                }); 
            }
        }
    }
    
</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('/menu/menu3.php');
ob_start();
?>
<div class="formulario"  >
            <form action="exercisesAdmin.php" method="post" onsubmit="return validateForm()">
                <fieldset>
                <legend><h3><?php echo(_("Añadir nuevo ejercicio"));?></h3></legend>
                <p><?php echo(_("Seleccione un documento a partir del que crear un ejercicio:"));?></p>
                <div class="blockformulario">
                <label><?php echo(_("Colección"));?></label>               
                <div id="combo_collection" style="width:200px; height:20px;"></div>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo = new dhtmlXCombo("combo_collection","comboCollection",200);
                    //dhtmlx.skin = 'dhx_skyblue';
                    combo.enableOptionAutoWidth(true);
                    combo.setOptionHeight(250);
                    combo.enableOptionAutoPositioning();
                    combo.loadXML("../controller/comboControllers/comboCollectionsAdmin.php");
                    combo.attachEvent("onChange", function(){
                        selectedCollection = combo.getSelectedValue();
                        combo2.clearAll(true);
                        combo2.loadXML("../controller/comboControllers/comboDocuments.php?idCollection="+selectedCollection);
                        combo6.clearAll(true);
                        combo6.loadXML("../controller/comboControllers/comboGroups.php?method=adminExercises&idCollection="+selectedCollection);  
                    });  
                </script>
                <label><?php echo(_("Nombre"));?></label>
                <input type="text" id="nombreejercicio" />
               
                </div>
                <div class="blockformulario">
                <label><?php echo(_("Documento"));?></label>               
                <div id="combo_document" style="width:200px; height:20px;"></div>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo2 = new dhtmlXCombo("combo_document","comboDocument",200);
                   // dhtmlx.skin = 'dhx_skyblue';
                    combo2.enableOptionAutoWidth(true);
                    combo2.enableOptionAutoHeight(true);
                    combo2.enableOptionAutoPositioning();
                </script>
                <label><?php echo(_("Grupo"));?></label>               
                <div id="combo_grupo" style="width:200px; height:20px;"></div>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo6 = new dhtmlXCombo("combo_grupo","comboGroups",200,'checkbox');
                    dhtmlx.skin = 'dhx_skyblue';
                    combo6.enableOptionAutoWidth(true);
                    combo2.enableOptionAutoHeight(true);
                    combo6.enableOptionAutoPositioning();                    
                </script>
                </div>
                <div class="blockformulario">
                <label><?php echo(_("Dificultad realización"));?></label>
                <select style='width:200px;'  id="combo_pistas" name="alfa1">
                    <option value="0"><?php echo(_("Fácil"));?></option>
                    <option value="1"><?php echo(_("Medio"));?></option>
                    <option value="2"><?php echo(_("Difícil"));?></option>
                </select>
                <script>
                    var combo3=dhtmlXComboFromSelect("combo_pistas");
                    //dhtmlx.skin = 'dhx_skyblue';
                    combo3.enableOptionAutoWidth(true);
                    combo3.enableOptionAutoHeight(true);
                    combo3.enableOptionAutoPositioning();
                </script>
                <label><?php echo(_("Modo corrección"));?></label>
                <select style='width:200px;'  id="combo_modo" name="alfa1">
                    <option value="0"><?php echo(_("Corregir al final"));?></option>
                    <option value="1"><?php echo(_("Corregir paso a paso"));?></option>
                </select>
                <script>
                    var combo5=dhtmlXComboFromSelect("combo_modo");
                    //dhtmlx.skin = 'dhx_skyblue';
                    combo5.enableOptionAutoWidth(true);
                    combo5.enableOptionAutoHeight(true);
                    combo5.enableOptionAutoPositioning();
                </script>
                <label><?php echo(_("Objetivo"));?></label>
                <select style='width:200px;'  id="combo_objetivo" name="alfa1">
                    <option value="0"><?php echo(_("% palabras acertadas"));?></option>
                    <option value="1"><?php echo(_("Nº máximo de fallos"));?></option>
                </select>
                <script>
                    var combo4=dhtmlXComboFromSelect("combo_objetivo");
                    //dhtmlx.skin = 'dhx_skyblue';
                    combo4.enableOptionAutoWidth(true);
                    combo4.enableOptionAutoHeight(true);
                    combo4.enableOptionAutoPositioning();
                </script>
                <input type="text" id="objetivo" size="4" />
                </div>
                <div class="buttonformulario">
                <input  type="submit" name="newExercise" value="<?php echo(_("Añadir"));?>" id="newExercise" />
                </div>
                </fieldset>
            </form>
        </div>
        
        
        
        <label><?php echo(_("Seleccione una colección:"));?></label>               
        <div id="combo_selectcollection" style="width:200px; height:20px;"></div>
        <script>
            window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
            var comboColeccion = new dhtmlXCombo("combo_selectcollection","comboCollection",200);
            //dhtmlx.skin = 'dhx_skyblue';
            comboColeccion.enableOptionAutoWidth(true);
            comboColeccion.setOptionHeight(400);
            comboColeccion.enableOptionAutoPositioning();
            comboColeccion.loadXML("../controller/comboControllers/comboCollectionsAdmin.php");
            comboColeccion.attachEvent("onChange", function(){
               selectedCollection = comboColeccion.getSelectedValue(); 
               mygrid.clearAll()
               mygrid.loadXML("../controller/gridControllers/gridExercisesAdmin.php?idCollection="+selectedCollection,onLoadFunction);
            });
        </script>
        
        <div class="gridAfterForm" id="gridExercises" style="width: 85%; height: 85%;top:550px !important;"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridExercises');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Código ejercicio"));?>, <?php echo(_("Ejercicio"));?>, <?php echo(_("Documento"));?>,  <?php echo(_("Dificultad realización"));?>, <?php echo(_("Objetivo"));?>,<?php echo(_("Modo corrección"));?>,<?php echo(_("Grupos"));?>,<?php echo(_("Ordenar"));?>,#cspan,<?php echo(_("Eliminar"));?>");
            mygrid.setInitWidths("90,*,*,90,210,170,90,40,40,90");
            mygrid.setColAlign("center,left,left,center,center,center,center,center,center,center");
            mygrid.setColTypes("ro,ed,ro,ro,ro,ro,ro,img,img,img,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,400);
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
                <a href="#close" id="closeModal" title="Close" class="close">X</a>
                    <h3><?php echo(_("Acceso a grupos:"));?></h3>
                    <label><?php echo(_("Ejercicio:"));?></label>
                    <label id="ejName"></label>
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
                    
                    <input  type="button" name="cancelar" onclick="cancelGroupPermissions()" value="<?php echo(_("Cancelar"));?>" id="cancelar" />
                    <input  type="submit" name="enviar" onclick="saveGroupPermissions()" value="<?php echo(_("Guardar"));?>" id="aceptarGestionGrupos" />
            </div>
        </div> 
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>