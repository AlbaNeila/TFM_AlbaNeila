<?php
session_start();
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
                method:"newCollection", collection: $("#nombrecoleccion").val(), description: $("#descripcioncoleccion").val(), groups: JSON.stringify(checked_array),ordered: $("#ordenadacoleccion")[0].selectedIndex
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
    
    function addEventsToImages(){
    $(window).ready(function() { 
        setTimeout(function() {
            var td;
            var img;
            var grupo;
            $('.objbox tr').each(function (index){
                 $(this).children("td").each(function (index2) {
                    if(index2 == 6){ //Imagen eliminar colección 
                        $(this).children("img").bind('click',function($this){
                            var idfila = $(this).attr("id");
                            var coleccion = mygrid.cells(idfila, 0).getValue();
                             var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar la colección"));?>'}),
                              ok = $('<button />', {text: 'Ok', click: function() {deleteCollection(coleccion);}}),
                              cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});
                        
                            dialogue( message.add(ok).add(cancel), '<?php echo(_("Confirmación eliminar colección"))?>'); 
                        });
                    }
                    if(index2 == 7){ //Imagen entrar
                        $(this).children("img").bind('click',function($this){
                            var idfila = $(this).attr("id");
                            var idColeccion = mygrid.cells(idfila, 0).getValue();
                            var coleccion = mygrid.cells(idfila, 1).getValue();
                            window.location.href = 'documentTeacher.php?coleccion='+coleccion+'&idColeccion='+idColeccion;
                        });
                    }
                });
            });
        },6000);
    });
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

    function deleteCollection(coleccion){
        if(coleccion!=""){
            var request = $.ajax({
              type: "POST",
              url: "../controller/collectionController.php",
              async: false,
              data: {
                method:"deleteCollection", coleccion: coleccion
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        mygrid.clearAll();
                        mygrid.loadXML("../controller/gridControllers/gridCollections.php",addEventsToImages);  
                    }
                    else{
                        alert("error");
                    }
            });
        }
    }
</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('/menu/menu1.php');
ob_start();
?>
        <div class="divForm" style="width:22%;min-width:278px;" action="collectionsTeacher.php" method="post" onsubmit="return validateForm()">
            <form>
                <h3><?php echo(_("Añadir nueva colección"));?></h3>
                <label><?php echo(_("Nombre"));?></label>
                <input type="text" id="nombrecoleccion">
                <label><?php echo(_("Descripción"));?></label>
                <input type="text" id="descripcioncoleccion" />
                <label><?php echo(_("Grupo"));?></label>
                
                <div id="combo_zone" style="width:200px; height:20px;"></div>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo = new dhtmlXCombo("combo_zone","comboGroups",200,'checkbox');
                    dhtmlx.skin = 'dhx_skyblue';
                    combo.enableOptionAutoWidth(true);
                    combo.enableOptionAutoHeight(true);
                    combo.enableOptionAutoPositioning();
                    combo.loadXML("../controller/comboControllers/comboGroups.php"); 
                </script>
                
                 <label><?php echo(_("Ordenada"));?></label>
                <select id="ordenadacoleccion" >
                  <option value="no"><?php echo(_("No"));?></option>
                  <option value="yes"><?php echo(_("Sí"));?></option>
                </select>
                <input  type="submit" name="newCollection" value="<?php echo(_("Añadir"));?>" id="newCollection" />
            </form>
        </div>
        
        <div id="gridCollections" style="width: 90%; height: 90%"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridCollections');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Código colección"));?>, <?php echo(_("Nombre"));?>, <?php echo(_("Descripción"));?>, <?php echo(_("Nº documentos"));?>, <?php echo(_("Nº grupos"));?>, <?php echo(_("Ordenada"));?>, <?php echo(_("Eliminar"));?>, <?php echo(_("Acceder"));?>");
            mygrid.setInitWidths("125,*,*,125,100,100,100,100");
            mygrid.setColAlign("left,left,left,center,center,center,center,center");
            mygrid.setColTypes("ro,ed,ed,ro,ro,co,img,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,200);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("false,true,true,false,false,false,false,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridCollections.php",addEventsToImages);  
            mygrid.attachEvent("onEditCell", function(stage,rId,cInd,nValue,oValue){
                if (stage == 2){
                    debugger;
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


