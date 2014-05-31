<?php
session_start();
include('../model/acceso_db.php');

ob_start();
?>
<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxCombo/codebase/dhtmlxcombo.css">

<script src="../lib/dhtmlxCombo/codebase/dhtmlxcommon.js"></script>
<script src="../lib/dhtmlxCombo/codebase/dhtmlxcombo.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_whp.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_extra.js"></script>


<script>
    function validateForm() {
        var flag = true;
        var u = check_empty($("#nombredoc"));
        var p = check_empty($("#descripciondoc"));
        var t = check_empty($("#tipoesc"));
        var f = check_empty($("#fechadoc"));
        var img = check_empty($("#imagen"));
        var tr = check_empty($("#transcripcion"));
        
        var checked_array = combo.getChecked();
        if(checked_array.length == 0){
            set_tooltip($("#combo_collection"),"<?php echo(_("Debe seleccionar al menos una colección"));?>");
            flag=false;
        }
        else{
            var collections='';
            for(i=0;i<checked_array.length;i++){
                collections+=checked_array[i]+';';
            }
            var input = document.createElement("input");
            input.setAttribute("type", "hidden");                           
            input.setAttribute("name", "idHidden");                            
            input.setAttribute("value", collections);
            document.getElementById("formDoc").appendChild(input);
        }
        
        if($("#transcripcion").val()!=""){
            var file = $("#transcripcion").val();
            var ext = file.split('.').pop().toLowerCase();
           if ($.inArray(ext, ['xml']) == -1) {
                set_tooltip($("#transcripcion"),"<?php echo(_("La transcripción debe ser un archivo con extensión .xml"));?>");
                flag = false;
            } 
        }
        
        if(u || p || t ||f || img || tr || !flag){
            flag= false;
        }
        else{
            var request = $.ajax({
              type: "POST",
              url: "../controller/documentController.php",
              async: false,
              data: {
                method:"checkNameDocument", document: $("#nombredoc").val(),
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "0"){
                        flag= false;
                        alert("error");
                    }
                    if($.trim(request) == "1"){
                        flag= true;
                    }
                    if($.trim(request) == "2"){
                        flag= false;
                        set_tooltip($("#nombredoc"),"<?php echo(_("Ya existe un documento con el mismo nombre. Por favor, introduzca un nombre de documento diferente."));?>");
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
    
    function deleteDoc(){
        var rowId = mygrid.getSelectedId();
        var idDoc = mygrid.cellById(rowId, 0).getAttribute("idDoc");


        var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar el documento?"));?>'}),
                      ok = $('<button />', {text: 'Ok', click: function() {deleteDocAdmin(idDoc);}}),
                      cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});                       
        dialogue( message.add(ok).add(cancel), '<?php echo(_("Confirmación eliminar documento"))?>'); 
    }
    
    function deleteDocAdmin(idDoc){
        if(idDoc!=""){
            var request = $.ajax({
              type: "POST",
              url: "../controller/documentController.php",
              async: false,
              data: {
                method:"deleteDoc", idDoc: idDoc
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        mygrid.clearAll();
                        mygrid.loadXML("../controller/gridControllers/gridDocumentsAdmin.php",true,true);
                    }
                    else{
                        alert("error");
                    }
            });
        }
    }
    
    function editFiles(){
        var rowId = mygrid.getSelectedId();
        var idDoc = mygrid.cellById(rowId, 0).getAttribute("idDoc");
        //Agregamos un hidden con el id del doc seleccionado
        var input = document.createElement("input");
        input.setAttribute("type", "hidden");                           
        input.setAttribute("id", "idHidden");                            
        input.setAttribute("value", idDoc);
        input.setAttribute("name","idDoc");
        var modal = document.getElementById("openModal");
        document.getElementById("formChangeDoc").appendChild(input);
        window.location = $('#anchorOpenModal').attr('href');
    }
    
    function validateChange() {
        var flag = true;
        var i = check_empty($("#changeimagen"));
        var tr = check_empty($("#changetranscripcion"));
        
        if($("#changetranscripcion").val()!=""){
            var file = $("#changetranscripcion").val();
            var ext = file.split('.').pop().toLowerCase();
           if ($.inArray(ext, ['xml']) == -1) {
                set_tooltip($("#changetranscripcion"),"<?php echo(_("La transcripción debe ser un archivo con extensión .xml"));?>");
                flag = false;
            } 
        }
        
        if(i || tr || !flag){
            flag= false;
        }
        return flag;
    }
    
    function showCollections(){
        var rowId = mygrid.getSelectedId();
        var idDoc = mygrid.cellById(rowId, 0).getAttribute("idDoc");
        //Agregamos un hidden con el id del doc seleccionado
        var input = document.createElement("input");
        input.setAttribute("type", "hidden");                           
        input.setAttribute("id", "idHidden");                            
        input.setAttribute("value", idDoc);
        input.setAttribute("name","idDoc");

        document.getElementById("gridGestionColecciones").appendChild(input);
        window.location = $('#anchorGestionarColecciones').attr('href');
        mygrid2.clearAll();
        mygrid2.loadXML("../controller/gridControllers/gridManageCollections.php?idSearched="+idDoc);
    }
    
    function saveCollectionsAccess(){
        var colecciones = new Array();
        var cont = 0;
        var idDoc=$("#idHidden").val();
        debugger;
            
        mygrid2.forEachRow(function(id){
            mygrid2.forEachCell(id,function(c){
               if(c.isChecked()){
                 colecciones[cont] = c.getAttribute("idCol");
                 cont++;
               }
            });
        });
        if(colecciones.length == 0){
            set_tooltip($("#gridGestionColecciones"),"<?php echo(_("El documento debe encontrarse al menos en una colección"));?>");
            return false;
        }

        var request = $.ajax({
          type: "POST",
          url: "../controller/collectionController.php",
          async: false,
          data: {
            method:"saveDocumentAccess", collections: JSON.stringify(colecciones), idDocument:idDoc
          },
          dataType: "script",   
        });
        request.success(function(request){
                if($.trim(request) == "1"){
                    $("#idHidden").remove();
                    window.location = $('#closeModal2').attr('href');
                }
                else{
                    $("#idHidden").remove();
                    alert("error");
                }
        });
        
    }
    
    function cancelCollectionsAcces(){
        $("#idHidden").remove();
        window.location = $('#closeModal2').attr('href');
    }
    
</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('/menu/menu2.php');
include('../init.php');
ob_start();
?>
    <div class="submenu">
        <div class="submenuitem"><a href="collectionsAdmin.php" ><?php echo(_("Colecciones"));?></a></div>
        <div class="submenuitem"><a href="documentAdmin.php" style="font-weight: bold"><?php echo(_("Documentos"));?></a></div>
    </div>
       
        <div class="formulario"  >
            <form method="post" enctype="multipart/form-data" id="formDoc" action="../controller/addDocumentController.php?method=addNewDocsAdmin" onsubmit="return validateForm()" >
                <fieldset>
                <legend><h3><?php echo(_("Añadir nuevo documento"));?></h3></legend>
                <div class="blockformulario">
                <label><?php echo(_("Nombre"));?></label>
                <input type="text" id="nombredoc" name="name">
                <label><?php echo(_("Descripción"));?></label>
                <input type="text" id="descripciondoc" name="description"/>
                </div>
                <div class="blockformulario">
                <label><?php echo(_("Tipo escritura"));?></label>
                <input type="text" id="tipoesc" name="type"/>
                <label><?php echo(_("Fecha"));?></label>
                <input type="text" id="fechadoc" name="date"/>
                </div>
                <div class="blockformulario">
                <label><?php echo(_("Colección"));?></label>               
                <div id="combo_collection" style="width:200px; height:20px;"></div>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo = new dhtmlXCombo("combo_collection","comboCollection",200,'checkbox');
                    //dhtmlx.skin = 'dhx_skyblue';
                    combo.enableOptionAutoWidth(true);
                    combo.setOptionHeight(250);
                    combo.enableOptionAutoPositioning();
                    combo.loadXML("../controller/comboControllers/comboCollectionsAdmin.php");  
                </script>
                <label><?php echo(_("Imagen"));?></label>
                <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                <input type="file" id="imagen" name="imagen"/>
                </div>
                <div class="blockformulario">
                <label><?php echo(_("Transcripción"));?></label>
                <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                <input type="file" id="transcripcion" name="transcripcion"/>
                </div>
                <div class="buttonformulario">
                <input  type="submit" name="newDoc" value="<?php echo(_("Añadir"));?>" id="newDoc" />
                </div>
                </fieldset>
            </form>

        </div>

        
        <div class="gridAfterForm" id="gridDocs" style="width: 85%; height: 85%"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridDocs');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Nombre"));?>, <?php echo(_("Descripción"));?>, <?php echo(_("Tipo escritura"));?>, <?php echo(_("Fecha"));?>, <?php echo(_("Ejercicios"));?>,<?php echo(_("Colecciones"));?>,<?php echo(_("Modificar ficheros"));?>, <?php echo(_("Eliminar"));?>");
            mygrid.setInitWidths("*,*,*,*,100,*,100,130");
            mygrid.setColAlign("left,left,left,left,center,center,center,center");
            mygrid.setColTypes("ed,ed,ed,ed,img,img,img,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,400);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("true,true,true,true,false,false,false,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridDocumentsAdmin.php");
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
                    var idDoc = mygrid.cellById(rId,0).getAttribute("idDoc");
                    
                    if(nValue == ""){
                        set_tooltip($('.cellSelected'),"<?php echo(_("No puede estar vacío."));?>");
                        return false;
                    }
                    else{
                        var request = $.ajax({
                          type: "POST",
                          url: "../controller/documentController.php",
                          async: false,
                          data: {
                            method:"checkUpdateGrid", row:JSON.stringify(row), idDoc:idDoc, 
                          }  
                        });
                        request.success(function(request){
                                if($.trim(request) == "1"){
                                    mygrid.cellById(rId, cInd).setValue(nValue); 
                                    mygrid.editStop();
                                    flag= true;
                                }
                                else{ 
                                    set_tooltip($('.cellSelected'),"<?php echo(_("Ya existe un documento con el mismo nombre. Por favor, introduzca un nombre de documento diferente."));?>");
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
            <div>
                
                <a href="#close" id="closeModal" title="Close" class="close">X</a>
                <form method="post" enctype="multipart/form-data" id="formChangeDoc" action="../controller/addDocumentController.php?method=changeDocsAdmin" onsubmit="return validateChange()" >
                    <h3><?php echo(_("Modificar ficheros"));?></h3>
                    <label><?php echo(_("Imagen"));?></label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                    <input type="file" id="changeimagen" name="changeimagen"/>
                    <label><?php echo(_("Transcripción"));?></label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                    <input type="file" id="changetranscripcion" name="changetranscripcion"/>
                    <input  type="button" name="cancelar" onclick="window.location = $('#closeModal').attr('href');  " value="<?php echo(_("Cancelar"));?>" id="cancelar" />
                    <input  type="submit" name="enviar"  value="<?php echo(_("Aceptar"));?>" id="changeFiles" />
                </form>
            </div>
        </div>
        
        <a href="#gestionarColecciones" id="anchorGestionarColecciones"></a>
        <div id="gestionarColecciones" class="modalDialog2">
            <div>
                <a href="#close" id="closeModal2" onclick="$('#idHidden').remove();"  title="Close" class="close">X</a>
                    <h3><?php echo(_("Gestionar colecciones"));?></h3>
                    
                    <div id="gridGestionColecciones" style="width: 100%; height: 100%"></div>
                    <script>
                        var mygrid2 = new dhtmlXGridObject('gridGestionColecciones');
                        mygrid2.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
                        mygrid2.setHeader("<?php echo(_("Colección"));?>, <?php echo(_("Asignar documento"));?>");
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
                    
                    <input  type="button" name="cancelar" onclick="cancelCollectionsAcces()" value="<?php echo(_("Cancelar"));?>" id="cancelar" />
                    <input  type="submit" name="enviar" onclick="saveCollectionsAccess()" value="<?php echo(_("Guardar"));?>" id="aceptarAccesoColecciones" />
            </div>
        </div>
         
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


