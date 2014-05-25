<?php
session_start();
ob_start();
?>
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
include ('/menu/menu1.php');
ob_start();
?>
        <div class="divForm" style="width:22%;min-width:278px;" action="groupTeacher.php" method="post" onsubmit="return validateForm()">
            <form>
                <h3><?php echo(_("USUARIOS"));?></h3>
                <label><?php echo(_("Nombre"));?></label>
                <input type="text" id="nombregrupo">
                <label><?php echo(_("Descripción"));?></label>
                <input type="text" id="descripciongrupo" />
                <input  type="submit" name="newTeacher" value="<?php echo(_("Añadir"));?>" id="newTeacher" />
            </form>
        </div>       
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>


