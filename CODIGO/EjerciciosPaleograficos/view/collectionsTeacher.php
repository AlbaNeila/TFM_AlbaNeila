<?php
session_start();
ob_start();
?>
<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxCombo/codebase/dhtmlxcombo.css">

<script src="../lib/dhtmlxCombo/codebase/dhtmlxcommon.js"></script>

<script src="../lib/dhtmlxCombo/codebase/dhtmlxcombo.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_whp.js"></script>
<script>

    function validateForm() {
        var u = check_empty($("#nombrecoleccion"));
        var p = check_empty($("#descripcioncoleccion"));
        debugger;
        var combo = combo.getSelectedIndex(); 
        var flag = false;
        
        if(u || p){
            flag= false;
        }
        else{
           var request = $.ajax({
              type: "POST",
              url: "../controller/collectionController.php",
              async: false,
              data: {
                method:"newCollection", collection: $("#nombrecoleccion").val(), description: $("#descripcioncoleccion").val(), group: combo.getActualValue(),ordered: $("#ordenadacoleccion")[0].selectedIndex
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        flag= true;
                    }
                    else{
                        flag= false;
                        set_tooltip($("#nombregrupo"),"<?php echo(_("Ya existe una colección con el mismo nombre. Por favor, introduzca un nombre de colección diferente."));?>");
                    }
            });
        }       
        return flag;
    }
</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('/menu/menuCollectionsTeacher.php');
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
                
                <div id="combo_zone" style="width:200px; height:30px;"></div>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo = new dhtmlXCombo("combo_zone","comboGroups",155);
                    combo.enableOptionAutoWidth(true);
                    combo.enableOptionAutoHeight(true);
                    combo.enableOptionAutoPositioning();
                    combo.loadXML("../controller/comboControllers/comboGroups.php"); 
                </script>
                
                 <label><?php echo(_("Ordenada"));?></label>
                <select id="ordenadacoleccion">
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
            mygrid.setHeader("Codigo colección, Nombre, Descripción, Nº documentos, Nº grupos, Ordenada, Eliminar");
            mygrid.setInitWidths("125,*,*,125,100,100,100");
            mygrid.setColAlign("left,left,left,center,center,center,center");
            mygrid.setColTypes("ro,ed,ed,ro,ro,co,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,200);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("false,true,true,false,false,false,false");
            mygrid.setSizes();
            mygrid.setSkin("light");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridCollections.php");  
            
        </script>
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


