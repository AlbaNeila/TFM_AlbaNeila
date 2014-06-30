<?php
session_start();
if($_SESSION['usuario_tipo'] != "ADMIN"){
    header('Location: ../view/login.php');
}
include("../model/persistence/acceso_db.php");

ob_start();
?>
<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxCombo/codebase/dhtmlxcombo.css">

<script src="../lib/dhtmlxCombo/codebase/dhtmlxcommon.js"></script>
<script src="../lib/dhtmlxCombo/codebase/dhtmlxcombo.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_whp.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_extra.js"></script>

<script>
    $(document).ready(function(){
       window.location = $('#closeModal').attr('href');
       window.location = $('#closeModal2').attr('href'); 
    });

    function validateForm() {
        var flag = true;
        var u = check_empty($("#nombredoc"),"<?php echo(_("Este campo es requerido"));?>");
        var p = check_empty($("#descripciondoc"),"<?php echo(_("Este campo es requerido"));?>");
        var t = check_empty($("#tipoesc"),"<?php echo(_("Este campo es requerido"));?>");
        var f = check_empty($("#fechadoc"),"<?php echo(_("Este campo es requerido"));?>");
        var img = check_empty($("#imagen"),"<?php echo(_("Este campo es requerido"));?>");
        var tr = check_empty($("#transcripcion"),"<?php echo(_("Este campo es requerido"));?>");
        
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
        
        if($("#imagen").val()!=""){
            var file = $("#imagen").val();
            var ext = file.split('.').pop().toLowerCase();
           if ($.inArray(ext, ['jpg','jpeg','png']) == -1) {
                set_tooltip($("#imagen"),"<?php echo(_("El formato de la imagen no es válido."));?>");
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
                        set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                    }
                    if($.trim(request) == "1"){
                        flag= true;
                        set_tooltip_general("<?php echo(_("Se añadió el documento correctamente."));?>"); 
                    }
                    if($.trim(request) == "2"){
                        flag= false;
                        set_tooltip($("#nombredoc"),"<?php echo(_("Ya existe un documento con el mismo nombre. Por favor, introduzca un nombre de documento diferente."));?>");
                    }
            });
        }
        return flag;
    }

    
    function deleteDoc(){
        var rowId = mygrid.getSelectedId();
        var idDoc = mygrid.cellById(rowId, 0).getAttribute("idDoc");
        var nameDoc = mygrid.cellById(rowId, 0).getValue();

        var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar el documento "));?>'+nameDoc+'?'}),
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
                        set_tooltip_general_error("<?php echo(_("Se eliminó el documento correctamente."));?>"); 
                    }
                    else{
                        set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                    }
            });
        }
    }
    
    function editFiles(){
        var rowId = mygrid.getSelectedId();
        var idDoc = mygrid.cellById(rowId, 0).getAttribute("idDoc");
        var nameDoc = mygrid.cellById(rowId, 0).getValue(); 
        //Agregamos un hidden con el id y el name del doc seleccionado
        var input = document.createElement("input");
        input.setAttribute("type", "hidden");                           
        input.setAttribute("id", "idHidden");                            
        input.setAttribute("value", idDoc);
        input.setAttribute("name","idDoc");
        $('#documentName').text(nameDoc);
        var modal = document.getElementById("openModal");
        document.getElementById("formChangeDoc").appendChild(input);
        window.location = $('#anchorOpenModal').attr('href');
    }
    
    function validateChange() {
        var flag = true;
        var i = check_empty($("#changeimagen"),"<?php echo(_("Este campo es requerido"));?>");
        var tr = check_empty($("#changetranscripcion"),"<?php echo(_("Este campo es requerido"));?>");
        
        if($("#changetranscripcion").val()!=""){
            var file = $("#changetranscripcion").val();
            var ext = file.split('.').pop().toLowerCase();
           if ($.inArray(ext, ['xml']) == -1) {
                set_tooltip($("#changetranscripcion"),"<?php echo(_("La transcripción debe ser un archivo con extensión .xml"));?>");
                flag = false;
            } 
        }
        
        if($("#changeimagen").val()!=""){
            var file = $("#changeimagen").val();
            var ext = file.split('.').pop().toLowerCase();
           if ($.inArray(ext, ['jpg','jpeg','png']) == -1) {
                set_tooltip($("#changeimagen"),"<?php echo(_("El formato de la imagen no es válido."));?>");
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
        var nameDoc = mygrid.cellById(rowId, 0).getValue(); 
        //Agregamos un hidden con el id del doc seleccionado
        var input = document.createElement("input");
        input.setAttribute("type", "hidden");                           
        input.setAttribute("id", "idHidden");                            
        input.setAttribute("value", idDoc);
        input.setAttribute("name","idDoc");

        $('#documentName2').text(nameDoc);
        document.getElementById("gridGestionColecciones").appendChild(input);
        window.location = $('#anchorGestionarColecciones').attr('href');
        mygrid2.clearAll();
        mygrid2.loadXML("../controller/gridControllers/gridManageCollections.php?idSearched="+idDoc);
    }
    
    function saveCollectionsAccess(){
        var colecciones = new Array();
        var cont = 0;
        var idDoc=$("#idHidden").val();
            
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
                    set_tooltip_general("<?php echo(_("Se actualizaron los permisos de acceso correctamente."));?>"); 
                }
                else{
                    $("#idHidden").remove();
                    set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                }
        });
        
    }
    
    function cancelCollectionsAcces(){
        $("#idHidden").remove();
        window.location = $('#closeModal2').attr('href');
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
include('../init.php');
ob_start();
?>
    <div class="submenu">
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="collectionsAdmin.php" ><?php echo(_("Colecciones"));?></a></div>
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="documentAdmin.php" style="font-weight: bold"><?php echo(_("Documentos"));?></a></div>
    </div>
       
        <div class="formulario"  >
            <form method="post" enctype="multipart/form-data" id="formDoc" action="../controller/addDocumentController.php?method=addNewDocsAdmin" onsubmit="return validateForm()" >
                <h2><?php echo(_("Nuevo documento"));?></h2>
                <table>
                    <tr>
                        <td class="td_label"><label><?php echo(_("Nombre"));?></label></td><td><input type="text" id="nombredoc" name="name"></td>
                        <td class="td_label"><label><?php echo(_("Descripción"));?></label></td><td><input type="text" id="descripciondoc" name="description"/></td>
                        <td class="td_label"><label><?php echo(_("Colección"));?></label></td><td><div id="combo_collection" style="width:200px; height:20px;"></div></td>
                    </tr>
                    <tr>
                        <td class="td_label"><label><?php echo(_("Tipo escritura"));?></label></td><td><input type="text" id="tipoesc" name="type"/></td>
                        <td class="td_label"><label><?php echo(_("Fecha"));?></label></td><td><input type="text" id="fechadoc" name="date"/></td>
                    </tr>
                    <tr>
                        <td class="td_label"><label><?php echo(_("Imagen"));?></label></td><td><input type="hidden" name="MAX_FILE_SIZE" value="40000000" />
                <input type="file" id="imagen" name="imagen" accept=".jpg, .png, .jpeg"/></td>
                        <td class="td_label"><label><?php echo(_("Transcripción"));?></label></td><td> <input type="hidden" name="MAX_FILE_SIZE" value="40000000" />
                <input type="file" id="transcripcion" name="transcripcion"/></td>
                    </tr>
                    <tr>
                        <td><input  type="submit" name="newDoc" value="<?php echo(_("Añadir"));?>" id="newDoc" /></td>
                    </tr>
                </table>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo = new dhtmlXCombo("combo_collection","comboCollection",200,'checkbox');
                    dhtmlx.skin = 'dhx_skyblue';
                    combo.enableOptionAutoWidth(true);
                    combo.setOptionHeight(250);
                    combo.enableOptionAutoPositioning();
                    combo.loadXML("../controller/comboControllers/comboCollectionsAdmin.php");  
                </script>
            </form>
        </div>

        
        <div class="gridAfterForm" id="gridDocs" style="width: 85%; height: 85%;top:350px;"></div>
<div id="labelAux"></div>
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
            mygrid.loadXML("../controller/gridControllers/gridDocumentsAdmin.php",onLoadFunction);
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
                
                <a href="#close" id="closeModal" title="<?php echo(_("Cerrar"));?>" class="close">X</a>
                <form method="post" enctype="multipart/form-data" id="formChangeDoc" action="../controller/addDocumentController.php?method=changeDocsAdmin" onsubmit="return validateChange()" >
                    <h3><?php echo(_("Modificar ficheros"));?></h3>
                    <label class="labelModal"><?php echo(_("Documento:"));?></label>
                    <label id="documentName"></label>
                    <p></p>
                    <label class="labelModal"><?php echo(_("Imagen:"));?></label><br>
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                    <input type="file" id="changeimagen" name="changeimagen" /><br>
                    <label class="labelModal"><?php echo(_("Transcripción:"));?></label><br>
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                    <input type="file" id="changetranscripcion" name="changetranscripcion"/><br><br />
                    <input  type="submit" class="buttonModal" name="enviar"  value="<?php echo(_("Aceptar"));?>" id="changeFiles" />
                    <input  type="button" class="buttonModal" name="cancelar" onclick="window.location = $('#closeModal').attr('href');  " value="<?php echo(_("Cancelar"));?>" id="cancelar" />
                </form>
            </div>
        </div>
        
        <a href="#gestionarColecciones" id="anchorGestionarColecciones"></a>
        <div id="gestionarColecciones" class="modalDialog2">
            <div>
                <a href="#close" id="closeModal2" onclick="$('#idHidden').remove();"  title="Close" class="close">X</a>
                    <h3><?php echo(_("Gestionar colecciones"));?></h3>
                    <label class="labelModal"><?php echo(_("Documento:"));?></label>
                    <label id="documentName2"></label>
                    <p></p>
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
                    <input  type="submit" class="buttonModal" name="enviar" onclick="saveCollectionsAccess()" value="<?php echo(_("Guardar"));?>" id="aceptarAccesoColecciones" />
                    <input  type="button" class="buttonModal" name="cancelar" onclick="cancelCollectionsAcces()" value="<?php echo(_("Cancelar"));?>" id="cancelar" />
            </div>
        </div>
         
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


