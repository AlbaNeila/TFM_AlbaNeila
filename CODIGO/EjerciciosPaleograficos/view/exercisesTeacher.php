<?php
session_start();
ob_start();
?>
<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxCombo/codebase/dhtmlxcombo.css">
<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxCombo/codebase/dhtmlx_custom.css">  
<script src="../lib/dhtmlxCombo/codebase/dhtmlxcommon.js"></script>
<script src="../lib/dhtmlxCombo/codebase/dhtmlxcombo.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_whp.js"></script>

<script>
    function validateForm() {
       var u = check_empty($("#nombregrupo"));
        var p = check_empty($("#descripciongrupo"));
        var flag = false;
        
        if(u || p){
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
include ('/menu/menuExercisesTeacher.php');
ob_start();
?>
        <div class="divForm" style="width:22%;min-width:278px;" action="groupTeacher.php" method="post" onsubmit="return validateForm()">
            <form>
                <h3><?php echo(_("Añadir nuevo ejercicio"));?></h3>
                <p><?php echo(_("Seleccione un documento a partir del que crear un ejercicio:"));?></p>
                <label><?php echo(_("Colección"));?></label>
                
                <div id="combo_collection" style="width:200px; height:20px;"></div>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo = new dhtmlXCombo("combo_collection","comboCollection",200);
                    dhtmlx.skin = 'dhx_skyblue';
                    combo.enableOptionAutoWidth(true);
                    combo.enableOptionAutoHeight(true);
                    combo.enableOptionAutoPositioning();
                    combo.loadXML("../controller/comboControllers/comboCollections.php");
                    combo.attachEvent("onChange", function(){
                        debugger;
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
                    dhtmlx.skin = 'dhx_skyblue';
                    combo2.enableOptionAutoWidth(true);
                    combo2.enableOptionAutoHeight(true);
                    combo2.enableOptionAutoPositioning();
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


