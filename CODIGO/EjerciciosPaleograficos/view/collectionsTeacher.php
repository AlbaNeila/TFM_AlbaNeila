<?php
session_start();
if($_SESSION['usuario_tipo'] != "PROFESOR"){
    header('Location: ../view/login.php');
}
ob_start();
include('../init.php');
?>
<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxCombo/codebase/dhtmlxcombo.css">
<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxCombo/codebase/dhtmlx_custom.css">  
<script src="../lib/dhtmlxCombo/codebase/dhtmlxcommon.js"></script>
<script src="../lib/dhtmlxCombo/codebase/dhtmlxcombo.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_whp.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_extra.js"></script>

<script>

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
    
    function accessCollection(){
        var rowId = mygrid.getSelectedId();
        var idColeccion = mygrid.cellById(rowId, 0).getValue();
        var coleccion = mygrid.cellById(rowId, 1).getValue();
        
        window.location.href = 'documentTeacher.php?coleccion='+coleccion+'&idColeccion='+idColeccion;
    }
    
    function deleteCollection(){
       var rowId = mygrid.getSelectedId();
       var idColeccion = mygrid.cellById(rowId, 0).getValue();
       if(idColeccion != 1){
           var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar la colección"));?>'}),
                          ok = $('<button />', {text: 'Ok', click: function() {deleteCollectionTeacher(idColeccion);}}),
                          cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});
        
            dialogue( message.add(ok).add(cancel), '<?php echo(_("Confirmación eliminar colección"))?>');
        }else{
            var cell = $('td.cellselected');
            set_tooltip_left(cell,"La colección Pública no puede eliminarse.")
        }
    }

    function deleteCollectionTeacher(idColeccion){
        if(idColeccion!=""){
            var request = $.ajax({
              type: "POST",
              url: "../controller/collectionController.php",
              async: false,
              data: {
                method:"deleteCollection", coleccion: idColeccion
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        mygrid.clearAll();
                        mygrid.loadXML("../controller/gridControllers/gridCollections.php");  
                    }
                    else{
                        alert("error");
                    }
            });
        }
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
include ('menu/menu1.php');
ob_start();
?>
    <div class="submenu">
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="collectionsTeacher.php" style="font-weight: bold"><?php echo(_("Colecciones"));?></a></div>
        <div class="submenuitem2"><img src="../public/img/menu2.png"><a><?php echo(_("Documentos"));?></a></div>
    </div>


        <div  class="formulario"   >
            <form action="collectionsTeacher.php" method="post" onsubmit="return validateForm()">
                <h2><?php echo(_("Añadir nueva colección"));?></h2>
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
                    combo.loadXML("../controller/comboControllers/comboGroups.php?method=otro"); 
                </script>  
            </form>
        </div>
        
        <div class="gridAfterForm" id="gridCollections" style="width: 85%; height: 85%;top: 300px;"></div>
<div id="labelAux"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridCollections');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Código colección"));?>, <?php echo(_("Nombre"));?>, <?php echo(_("Descripción"));?>, <?php echo(_("Nº documentos"));?>, <?php echo(_("Nº grupos"));?>, <?php echo(_("Eliminar"));?>, <?php echo(_("Acceder"));?>");
            mygrid.setInitWidths("90,*,400,100,90,90,90");
            mygrid.setColAlign("center,left,left,center,center,center,center");
            mygrid.setColTypes("ro,ed,ed,ro,ro,img,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,200);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("false,true,true,false,false,false,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridCollections.php",onLoadFunction);  
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
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


