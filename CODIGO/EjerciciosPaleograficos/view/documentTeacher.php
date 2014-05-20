<?php
session_start();
ob_start();
$coleccion=$_REQUEST['coleccion'];
$idColeccion=$_REQUEST['idColeccion'];
?>
<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxCombo/codebase/dhtmlxcombo.css">

<script src="../lib/dhtmlxCombo/codebase/dhtmlxcommon.js"></script>
<script src="../lib/dhtmlxCombo/codebase/dhtmlxcombo.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_whp.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_extra.js"></script>

<script>

    function validateForm() {
        var flag = false;
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
        <h3><?php echo(_("Colección: ")); echo $coleccion;?></h3>
        
        <div class="divForm" style="width:22%;min-width:278px;" action="documentTeacher.php" method="post" onsubmit="return validateForm()">
            <form>
                <h3><?php echo(_("Añadir nuevo documento"));?></h3>
                <label><?php echo(_("Nombre"));?></label>
                <input type="text" id="nombredoc">
                <label><?php echo(_("Descripción"));?></label>
                <input type="text" id="descripciondoc" />
                <label><?php echo(_("Tipo escritura"));?></label>
                <input type="text" id="tipoesc" />
                <label><?php echo(_("Fecha"));?></label>
                <input type="text" id="fechadoc" />
                <label><?php echo(_("Imagen"));?></label>
                <input type="file" id="imagen" />
                <label><?php echo(_("Transcripción"));?></label>
                <input type="text" id="transcripcion" />
                <input  type="submit" name="newDoc" value="<?php echo(_("Añadir"));?>" id="newDoc" />
            </form>
        </div>
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


