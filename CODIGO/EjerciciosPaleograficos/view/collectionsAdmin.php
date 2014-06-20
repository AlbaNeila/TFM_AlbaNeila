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
        var flag = true;
        var u = check_empty($("#nombrecoleccion"));
        var p = check_empty($("#descripcioncoleccion"));

        var checked_array = combo.getChecked();
        if(checked_array.length == 0){
            set_tooltip($("#combo_zone"),"<?php echo(_("Debe seleccionar al menos un grupo"));?>");
            flag=false;
        }
        
        if(u || p || !flag){
            flag= false;
        }
        else{
           var request = $.ajax({
              type: "POST",
              url: "../controller/collectionController.php",
              async: false,
              data: {
                method:"newCollection", collection: $("#nombrecoleccion").val(), description: $("#descripcioncoleccion").val(), groups: JSON.stringify(checked_array)
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
                        set_tooltip($("#nombrecoleccion"),"<?php echo(_("Ya existe una colección con el mismo nombre. Por favor, introduzca un nombre de colección diferente."));?>");
                    }
            });
        }       
        return flag;
    }
    
    function consultGroups(){
        var rowId = mygrid.getSelectedId();
        var idCollection = mygrid.cellById(rowId, 0).getValue();
        if(idCollection != 1){
            $("#idCollection").val(idCollection);
            $("#collectionName").html(mygrid.cellById(rowId, 1).getValue());
            mygrid2.clearAll();
            mygrid2.loadXML("../controller/gridControllers/gridManageGroups.php?idSearched="+idCollection+"&method=collectionAdmin");
            window.location = $('#anchorOpenModal').attr('href');
        }else{
            var cell = $('td.cellselected');
            set_tooltip_left(cell,"A la colección Pública tienen acceso todos los grupos de la aplicación. Esto no se puede modificar.")
        } 
    }
    
    function deleteCollection(){
        var rowId = mygrid.getSelectedId();
        var idCollection = mygrid.cellById(rowId, 0).getValue();
        if(idCollection != 1){
            var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar la colección?"));?>'}),
                          ok = $('<button />', {text: 'Ok', click: function() {deleteCollectionAdmin(idCollection);}}),
                          cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});                       
            dialogue( message.add(ok).add(cancel), '<?php echo(_("Confirmación eliminar colección"))?>'); 
        }else{
            var cell = $('td.cellselected');
            set_tooltip_left(cell,"La colección Pública no puede eliminarse.")
        }
    }
    
    function deleteCollectionAdmin(idCollection){
        if(idCollection!=""){
            var request = $.ajax({
              type: "POST",
              url: "../controller/collectionController.php",
              async: false,
              data: {
                method:"deleteCollection", coleccion: idCollection
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        mygrid.clearAll();
                        mygrid.loadXML("../controller/gridControllers/gridCollectionsAdmin.php",false,true); 
                    }
                    else{
                        alert("error");
                    }
            });
        }
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
                method:"updatePermissionsGroup", groups: JSON.stringify(groups),permissions:JSON.stringify(permissions),idCollection:$("#idCollection").val()
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        window.location = $('#closeModal').attr('href');
                        mygrid.updateFromXML("../controller/gridControllers/gridCollectionsAdmin.php");
                    }
                    else{
                        alert("error");
                    }
            });
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
include ('/menu/menu2.php');
ob_start();
?>
    <div class="submenu">
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="collectionsAdmin.php" style="font-weight: bold"><?php echo(_("Colecciones"));?></a></div>
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="documentAdmin.php"><?php echo(_("Documentos"));?></a></div>
    </div>

   <div  class="formulario"   >
            <form action="collectionsAdmin.php" method="post" onsubmit="return validateForm()">
                <h2><?php echo(_("Nueva colección"));?></h2>
                <table>
                    <tr>
                        <td class="td_label"><label><?php echo(_("Nombre"));?></label></td><td><input type="text" id="nombrecoleccion"></td>
                        <td class="td_label"><label><?php echo(_("Descripción"));?></label></td><td><input type="text" id="descripcioncoleccion" /></td>
                    </tr>
                    <tr>
                        <td class="td_label"><label><?php echo(_("Grupo"));?></label></td><td><div id="combo_zone" style="width:200px; height:20px;"></div></td>
                    </tr>
                    <tr>
                        <td><input  type="submit" name="newCollection" value="<?php echo(_("Añadir"));?>" id="newCollection" /></td>
                    </tr>
                </table>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo = new dhtmlXCombo("combo_zone","comboGroups",200,'checkbox');
                    dhtmlx.skin = 'dhx_skyblue';
                    combo.enableOptionAutoWidth(true);
                    combo.enableOptionAutoHeight(true);
                    combo.enableOptionAutoPositioning();
                    combo.loadXML("../controller/comboControllers/comboGroups.php?method=admin"); 
                </script> 
            </form>
        </div>
        
        <div class="gridAfterForm" id="gridCollections" style="width: 85%; height: 85%;top:350px;"></div>
        <div id="labelAux"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridCollections');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Código colección"));?>, <?php echo(_("Nombre"));?>, <?php echo(_("Descripción"));?>, <?php echo(_("Nº documentos"));?>, <?php echo(_("Nº grupos"));?>,<?php echo(_("Consultar grupos"));?>, <?php echo(_("Eliminar"));?>");
            mygrid.setInitWidths("100,*,*,100,100,100,100");
            mygrid.setColAlign("center,left,left,center,center,center,center");
            mygrid.setColTypes("ro,ed,ed,ro,ro,co,img,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,400);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("false,true,true,false,false,false,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridCollectionsAdmin.php",onLoadFunction);  
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
                          url: "../controller/collectionController.php",
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
                                    set_tooltip($('.cellSelected'),"<?php echo(_("Ya existe una colección con el mismo nombre. Por favor, introduzca un nombre de colección diferente."));?>");
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
                    <h3><?php echo(_("Gestionar acceso de grupos:"));?></h3>
                    <label class="labelModal"><?php echo(_("Colección:"));?></label>
                    <label id="collectionName"></label>
                    <p></p>
                    <input type="hidden" id="idCollection" name="idCollection">                    
                    <div id="gridGestionGrupos" style="width: 100%; height: 100%"></div>
                    <script>
                        var mygrid2 = new dhtmlXGridObject('gridGestionGrupos');
                        mygrid2.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
                        mygrid2.setHeader("<?php echo(_("Grupo"));?>, <?php echo(_("Permitir acceso"));?>, <?php echo(_("Denegar acceso"));?>");
                        mygrid2.setInitWidths("*,90,90");
                        mygrid2.setColAlign("center,center,center");
                        mygrid2.setColTypes("ro,ro,ro");
                        mygrid2.enableSmartRendering(true);
                        mygrid2.enableAutoHeight(true,200);
                        mygrid2.enableAutoWidth(true);
                        mygrid2.enableTooltips("true,false,false");
                        mygrid2.setSizes();
                        mygrid2.setSkin("dhx_skyblue");
                        mygrid2.init();
                    </script>
                    
                    <input  type="submit" class="buttonModal" name="enviar" onclick="saveGroupPermissions()" value="<?php echo(_("Guardar"));?>" id="aceptarGestionGrupos" />
                    <input  type="button" class="buttonModal" name="cancelar" onclick="window.location = $('#closeModal').attr('href');" value="<?php echo(_("Cancelar"));?>" id="cancelar" />
            </div>
        </div>   
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>


