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
    function validateForm() {
       var u = check_empty($("#nombreejercicio"));
        var p = check_empty($("#objetivo"));
        var flag = false;
        debugger;
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
              url: "../controller/groupController.php",
              async: false,
              data: {
                method:"newGroup", grupo: $("#nombregrupo").val(), descripcion: $("#descripciongrupo").val()
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        flag= true;
                    }
                    else{
                        flag= false;
                        set_tooltip($("#nombregrupo"),"<?php echo(_("Ya existe un grupo con el mismo nombre. Por favor, introduzca un nombre de grupo diferente."));?>");
                    }
            });
        }       
        return flag;
    }
    
</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('/menu/menu3.php');
ob_start();
?>
        <div class="divForm" style="width:22%;min-width:278px;" action="exercisesTeacher.php" method="post" onsubmit="return validateForm()">
            <form>
                <h3><?php echo(_("Añadir nuevo ejercicio"));?></h3>
                <p><?php echo(_("Seleccione un documento a partir del que crear un ejercicio:"));?></p>
                <label><?php echo(_("Colección"));?></label>
                
                <div id="combo_collection" style="width:200px; height:20px;"></div>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo = new dhtmlXCombo("combo_collection","comboCollection",200);
                    //dhtmlx.skin = 'dhx_skyblue';
                    combo.enableOptionAutoWidth(true);
                    combo.enableOptionAutoHeight(true);
                    combo.enableOptionAutoPositioning();
                    combo.loadXML("../controller/comboControllers/comboCollections.php");
                    combo.attachEvent("onChange", function(){
                        var selectedCollection = combo.getSelectedValue();
                        combo2.clearAll(true);
                        combo2.loadXML("../controller/comboControllers/comboDocuments.php?idCollection="+selectedCollection); 
                    });  
                </script>
                
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
                <hr>
                <label><?php echo(_("Nombre"));?></label>
                <input type="text" id="nombreejercicio" />
                <label><?php echo(_("Pistas"));?></label>
                <select style='width:200px;'  id="combo_pistas" name="alfa1">
                    <option value="1"><?php echo(_("Fácil"));?></option>
                    <option value="2"><?php echo(_("Medio"));?></option>
                    <option value="2"><?php echo(_("Difícil"));?></option>
                </select>
                <script>
                    var combo3=dhtmlXComboFromSelect("combo_pistas");
                    //dhtmlx.skin = 'dhx_skyblue';
                    combo3.enableOptionAutoWidth(true);
                    combo3.enableOptionAutoHeight(true);
                    combo3.enableOptionAutoPositioning();
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
                <label><?php echo(_("Grupo"));?></label>               
                <div id="combo_grupo" style="width:200px; height:20px;"></div>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo6 = new dhtmlXCombo("combo_grupo","comboGroups",200,'checkbox');
                    dhtmlx.skin = 'dhx_skyblue';
                    combo6.enableOptionAutoWidth(true);
                    combo6.enableOptionAutoHeight(true);
                    combo6.enableOptionAutoPositioning();
                    combo6.loadXML("../controller/comboControllers/comboGroups.php"); 
                </script>
                
                <input  type="submit" name="newTeacher" value="<?php echo(_("Añadir"));?>" id="newExercise" />
            </form>
        </div>
        <div id="gridExercises" style="width: 90%; height: 90%"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridExercises');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Código ejercicio"));?>, <?php echo(_("Ejercicio"));?>, <?php echo(_("Documento"));?>, <?php echo(_("Grupos"));?>, <?php echo(_("Pistas"));?>, <?php echo(_("Objetivo"));?>,<?php echo(_("Modo corrección"));?>,<?php echo(_("Eliminar"));?>");
            mygrid.setInitWidths("130,*,*,130,*,*,*,100");
            mygrid.setColAlign("left,left,left,left,left,left,left,center");
            mygrid.setColTypes("ro,ed,ro,ro,ro,ro,ro,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,200);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("false,true,true,false,false,false,false,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
         //   mygrid.loadXML("../controller/gridControllers/gridGroups.php",addEventsToImages);  
        </script>

<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>


