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
    <div class="submenu">
        <div class="submenuitem"><a href="usersAdmin.php" style="font-weight: bold"><?php echo(_("Alumnos"));?></a></div>
        <div class="submenuitem"><a href="#"><?php echo(_("Profesores"));?></a></div>
        <div class="submenuitem"><a href="#"><?php echo(_("Grupos"));?></a></div>
    </div>

    <div class="formulario"  action="groupTeacher.php" method="post" onsubmit="return validateForm()">
        <form>
            <fieldset>
            <legend><h3><?php echo(_("Nuevo alumno"));?></h3></legend>
            <div class="blockformulario">
                <label><?php echo(_("Nombre"));?></label>
                <input type="text" id="nombrealumno">              
                <label><?php echo(_("DNI"));?></label>
                <input type="text" id="dnialumno" />
            </div>
            <div class="blockformulario">
                <label><?php echo(_("Apellidos"));?></label>
                <input type="text" id="apellidosalumno" />
                <label><?php echo(_("Contraseña"));?></label>
                <input type="text" id="passwordalumno" />
            </div>
            <div class="blockformulario">
                <label><?php echo(_("Email"));?></label>
                <input type="text" id="emailalumno"/>
            </div>
            <div style="clear: both">
            <input  type="submit" name="newStudent" value="<?php echo(_("Crear"));?>" id="newStudent" />
            </div>
            </fieldset>        
        </form>
    </div>       
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>


