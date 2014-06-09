<?php
session_start();
include('../model/acceso_db.php');
$coleccion="";
$idColeccion="";
if(isset($_REQUEST['coleccion'])){
$coleccion=$_REQUEST['coleccion'];
}
if(isset($_REQUEST['idColeccion'])){
$idColeccion=$_REQUEST['idColeccion'];
}
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
    
    function editFiles(){
        var rowId = mygrid.getSelectedId();
        var doc = mygrid.cellById(rowId, 0).getAttribute('idDoc');
        //Agregamos un hidden con el id del doc seleccionado
        var input = document.createElement("input");
        input.setAttribute("type", "hidden");                           
        input.setAttribute("id", "idHidden");                            
        input.setAttribute("value", doc);
        input.setAttribute("name","idDoc");
        var modal = document.getElementById("openModal");
        document.getElementById("formChangeDoc").appendChild(input);
        window.location = $('#anchorOpenModal').attr('href');
    }
    
    function deleteDocument(){
        var rowId = mygrid.getSelectedId();
        var doc = mygrid.cellById(rowId, 0).getAttribute('idDoc');
        var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar el documento? Se eliminarán también los ejercicios creados a partir de él."));?>'}),
                      ok = $('<button />', {text: 'Ok', click: function() {deleteDoc(doc);}}),
                      cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});
    
        dialogue( message.add(ok).add(cancel), '<?php echo(_("Confirmación eliminar documento"))?>'); 
    }
    
    function deleteDoc(doc){
        if(doc!=""){
            var request = $.ajax({
              type: "POST",
              url: "../controller/documentController.php",
              async: false,
              data: {
                method:"deleteDoc", idDoc: doc
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        mygrid.clearAll();
                        mygrid.loadXML("../controller/gridControllers/gridDocuments.php?idColeccion="+<?php echo $idColeccion;?>);
                    }
                    else{
                        alert("error");
                    }
            });
        }
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
    
    function saveGroupPermissions(){
        var groups = new Array();
        var permissions = new Array();
        var cont=0;
        var cont2=0;
        mygrid2.forEachRow(function(id){
             groups[cont] = mygrid2.cellById(id,0).getAttribute("idGroup");
             cont++;
        });
        
         $('#gridGestionGrupos .objbox tr').each(function (index){
            $(this).children("td").each(function (index2) {
                if(index2 == 1){ //Permitir
                    if($(this).children("input").is(':checked')){ 
                       permissions[cont2]=true;
                    }else{
                        permissions[cont2]=false;
                    }
                    cont2++;
                }
            });
         });
        
        var request = $.ajax({
              type: "POST",
              url: "../controller/documentController.php",
              async: false,
              data: {
                method:"updatePermissionsGroup", groups: JSON.stringify(groups),permissions:JSON.stringify(permissions),idCollection:$("#idColeccion").val()
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

</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('/menu/menu1.php');
include('../init.php');
ob_start();
?>
   <div class="submenu">
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="collectionsTeacher.php"><?php echo(_("Colecciones"));?></a></div>
        <div class="submenuitem2"><img src="../public/img/menu2.png"><a style="font-weight: bold"><?php echo(_("Documentos"));?></a></div>
    </div>
    
        
        
        <div class="formulario"  >
            <form method="post" enctype="multipart/form-data" id="formDoc" action="../controller/addDocumentController.php?method=addNewDocs" onsubmit="return validateForm()" >
                <input type="hidden" name="coleccion" value="<?php echo $coleccion;?>">
                <input type="hidden" name="idColeccion" value="<?php echo $idColeccion;?>">
                <h2><?php echo(_("Añadir nuevo documento"));?></h2>
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
                        <td class="td_label"><label><?php echo(_("Imagen"));?></label></td><td><input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                <input type="file" id="imagen" name="imagen"/></td>
                        <td class="td_label"><label><?php echo(_("Transcripción"));?></label></td><td><input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                <input type="file" id="transcripcion" name="transcripcion"/></td>
                    </tr>
                    <tr>
                        <td><input  type="submit" name="newDoc" value="<?php echo(_("Añadir"));?>" id="newDoc" /></td>
                    </tr>
                </table>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo = new dhtmlXCombo("combo_collection","comboCollection",200,'checkbox');
                    //dhtmlx.skin = 'dhx_skyblue';
                    combo.enableOptionAutoWidth(true);
                    combo.setOptionHeight(true);
                    combo.enableOptionAutoPositioning();
                    combo.loadXML("../controller/comboControllers/comboCollections.php");  
                </script>
            </form>
        </div>
        
        <div class="formulario" style="top:320px;" >
            <h2><?php echo $coleccion;?></h2>           
        </div>
        <div class="formulario" style="top:320px; text-align: right;width: 85%;" >
            <h3><a href="#gestionarGrupos"><?php echo(_("Gestionar grupos"));?></a></h3>
        </div>

        
        <div id="gestionarGrupos" class="modalDialog2">
            <div>
                <a href="#close" id="closeModal2" title="Close" class="close2">X</a>
                    <h3><?php echo(_("Gestionar grupos"));?></h3>
                    <label><?php echo $coleccion;?></label>
                    <input type="hidden" id="coleccion" name="coleccion" value="<?php echo $coleccion;?>">
                    <input type="hidden" id="idColeccion" name="idColeccion" value="<?php echo $idColeccion;?>">
                    
                    <div id="gridGestionGrupos" style="width: 100%; height: 100%"></div>
                    <script>
                        var mygrid2 = new dhtmlXGridObject('gridGestionGrupos');
                        mygrid2.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
                        mygrid2.setHeader("<?php echo(_("Grupo"));?>, <?php echo(_("Permitir acceso"));?>, <?php echo(_("Denegar acceso"));?>");
                        mygrid2.setInitWidths("*,*,*");
                        mygrid2.setColAlign("center,center,center");
                        mygrid2.setColTypes("ro,ro,ro");
                        mygrid2.enableSmartRendering(true);
                        mygrid2.enableAutoHeight(true,200);
                        mygrid2.enableAutoWidth(true);
                        mygrid2.enableTooltips("true,false,false");
                        mygrid2.setSizes();
                        mygrid2.setSkin("dhx_skyblue");
                        mygrid2.init();
                        mygrid2.loadXML("../controller/gridControllers/gridManageGroups.php?idSearched="+<?php echo $idColeccion;?>+"&method=collection");
                    </script>
                    
                    <input  type="button" name="cancelar" onclick="window.location = $('#closeModal').attr('href');" value="<?php echo(_("Cancelar"));?>" id="cancelar" />
                    <input  type="submit" name="enviar" onclick="saveGroupPermissions()" value="<?php echo(_("Guardar"));?>" id="aceptarGestionGrupos" />
            </div>
        </div>
        
        <div class="gridAfterForm" id="gridDocs" style="width: 85%; height: 85%;top:380px;"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridDocs');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Nombre"));?>, <?php echo(_("Descripción"));?>, <?php echo(_("Tipo escritura"));?>, <?php echo(_("Fecha"));?>, <?php echo(_("Ejercicios"));?>, <?php echo(_("Ordenar"));?>, <?php echo(_("Eliminar"));?>, <?php echo(_("Modificar ficheros"));?>");
            mygrid.setInitWidths("*,*,*,*,100,100,100,130");
            mygrid.setColAlign("left,left,left,left,center,center,center,center");
            mygrid.setColTypes("ed,ed,ed,ed,img,co,img,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,200);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("true,true,true,true,false,false,false,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridDocuments.php?idColeccion="+<?php echo $idColeccion;?>);
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
                <form method="post" enctype="multipart/form-data" id="formChangeDoc" action="../controller/addDocumentController.php?method=changeDocs" onsubmit="return validateChange()" >
                    <h3><?php echo(_("Modificar ficheros"));?></h3>
                    <input type="hidden" name="coleccion" value="<?php echo $coleccion;?>">
                    <input type="hidden" name="idColeccion" value="<?php echo $idColeccion;?>">
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
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


