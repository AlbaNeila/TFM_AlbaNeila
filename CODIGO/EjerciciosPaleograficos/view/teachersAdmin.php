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
       window.location = $('#closeModal2').attr('href');
       window.location = $('#closeModal').attr('href');
    });

    function validateForm() {
       var empty=false;
       var flag = true;
       $("#formTeachers").find(':input').each(function() {              
            if(!empty){
                empty = check_empty(this,"<?php echo(_("Este campo es requerido"));?>");
            }else{
                check_empty(this,"<?php echo(_("Este campo es requerido"));?>");
                flag=false;
            }
        });
            
        if(!empty){
            if(!check_dni($("#dniprofesor"))){
                set_tooltip($("#dniprofesor"),"<?php echo(_("DNI no válido"));?>");
                flag = false;
            }
            if(!check_email($("#emailprofesor"))){
                set_tooltip($("#emailprofesor"),"<?php echo(_("Formato no válido"));?>");
                flag = false;
            }
            if(!check_names($("#nombreprofesor"))){
                set_tooltip($("#nombreprofesor"),"<?php echo(_("Formato no válido"));?>");
                flag = false;
            }
            if(!check_names($("#apellidosprofesor"))){
                set_tooltip($("#apellidosprofesor"),"<?php echo(_("Formato no válido"));?>");
                flag = false;
            }
            if(!check_password("#password")){
                set_tooltip($("#password"),"<?php echo(_("Debe contener entre 8-10 caracteres, al menos un dígito y un alfanumérico"));?>");
                flag = false;
            }else{
                    if(!check_password("#password2")){
                        set_tooltip($("#password2"),"<?php echo(_("Debe contener entre 8-10 caracteres, al menos un dígito y un alfanumérico"));?>");
                        flag = false;
                    }
                    else{
                        if(!check_passwords()){
                            set_tooltip($("#password2"),"<?php echo(_("Las contraseñas no coinciden"));?>");
                            flag = false;
                        }
                    }
                }
            
        }
        
        if(flag){
           var request = $.ajax({
              type: "POST",
              url: "../controller/userController.php",
              async: false,
              data: {
                method:"newTeacher", dniprofesor: $("#dniprofesor").val(), emailprofesor: $("#emailprofesor").val(),nombreprofesor:$("#nombreprofesor").val(),apellidosprofesor:$('#apellidosprofesor').val(),passwordprofesor:$('#passwordprofesor').val(),
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "0"){
                        flag= false;
                        set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                    }
                    if($.trim(request) == "1"){
                        set_tooltip_general("<?php echo(_("Se añadió el profesor correctamente."));?>"); 
                    }
                    if($.trim(request) == "2"){
                        flag= false;
                        set_tooltip($("#dniprofesor"),"<?php echo(_("Ya existe un usuario con el mismo DNI."));?>");
                    }
            });
        }       
        return flag;
    }
    
    function consultGroups(){
        var rowId = mygrid.getSelectedId();
        var idTeacher = mygrid.cellById(rowId, 0).getValue();
        $("#idTeacher").val(idTeacher);
        $("#teacherName").html(mygrid.cellById(rowId, 1).getValue());
        $("#teacherSurnames").html(mygrid.cellById(rowId, 2).getValue());
        mygrid2.clearAll();
        mygrid2.loadXML("../controller/gridControllers/gridTeacherGroups.php?idSearched="+idTeacher);
        window.location = $('#anchorOpenModal').attr('href'); 
    }
    
    function deleteTeacher(){
        var rowId = mygrid.getSelectedId();
        var idUser = mygrid.cellById(rowId, 0).getValue();
        var nameUser = mygrid.cellById(rowId, 1).getValue();
        var surnameUser = mygrid.cellById(rowId, 2).getValue();

        var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar el profesor "));?>'+nameUser+' ' +surnameUser+'?'}),
                      ok = $('<button />', {text: 'Ok', click: function() {deleteTeacherAdmin(idUser);}}),
                      cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});                       
        dialogue( message.add(ok).add(cancel), '<?php echo(_("Confirmación eliminar profesor"))?>'); 
    }
    
    function deleteTeacherAdmin(idUser){
        if(idUser!=""){
            var request = $.ajax({
              type: "POST",
              url: "../controller/userController.php",
              async: false,
              data: {
                method:"deleteUser", idUser: idUser
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        mygrid.updateFromXML("../controller/gridControllers/gridTeachersAdmin.php",false,true); 
                        set_tooltip_general("<?php echo(_("Se eliminó el profesor correctamente."));?>"); 
                    }
                    else{
                        set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                    }
            });
        }
    }
    
    function changePassword(){
        var rowId = mygrid.getSelectedId();
        var idUser = mygrid.cellById(rowId, 5).getAttribute('idTeacher');
        
        $("#idStudent2").val(idUser);
        $("#studentName2").html(mygrid.cellById(rowId, 1).getValue());
        
        window.location = $('#anchorOpenModal2').attr('href'); 
    }
    
    function saveNewPassword(){
        if(!check_password("#changepassword")){
            set_tooltip($("#changepassword"),"<?php echo(_("Debe contener entre 8-10 caracteres, al menos un dígito y un alfanumérico"));?>");
            flag = false;
        }else{
            if(!check_password("#changepassword2")){
                set_tooltip($("#changepassword2"),"<?php echo(_("Debe contener entre 8-10 caracteres, al menos un dígito y un alfanumérico"));?>");
                flag = false;
            }else{
                if(($("#changepassword").val() != $("#changepassword2").val())){
                     set_tooltip($("#changepassword2"),"<?php echo(_("Las contraseñas no coinciden"));?>");
                    return false;
                }else{ //Todo bien
                    var request = $.ajax({
                      type: "POST",
                      url: "../controller/userController.php",
                      async: false,
                      data: {
                        method:"updatePassword", newPass:$("#changepassword").val(), idUser:   $("#idStudent2").val()
                      },
                      dataType: "script",   
                    });
                    request.success(function(request){
                            if($.trim(request) == "1"){
                                window.location = $('#closeModal2').attr('href');
                                set_tooltip_general("<?php echo(_("Se modificó la contraseña correctamente."));?>"); 
                            }
                            else{
                                set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                            }
                    });
                }
            }
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
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="usersAdmin.php" ><?php echo(_("Alumnos"));?></a></div>
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="teachersAdmin.php"  style="font-weight: bold"><?php echo(_("Profesores"));?></a></div>
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="groupsAdmin.php"><?php echo(_("Grupos"));?></a></div>
    </div>

    <div class="formulario"  >
        <form id="formTeachers" action="teachersAdmin.php" class="formsAdd" method="post" onsubmit="return validateForm()">
            <h2><?php echo(_("Nuevo profesor"));?></h2>
            <table>
                <tr>
                     <td class="td_label"><label><?php echo(_("Nombre"));?></label></td><td><input type="text" id="nombreprofesor"></td>
                     <td class="td_label"><label><?php echo(_("Apellidos"));?></label></td><td><input type="text" id="apellidosprofesor" /></td>
                     <td class="td_label"><label><?php echo(_("Email"));?></label></td><td><input type="text" id="emailprofesor"/></td>
                </tr>
                 <tr>
                     <td class="td_label"><label><?php echo(_("DNI"));?></label></td><td><input type="text" id="dniprofesor" /></td>
                     <td class="td_label"><label><?php echo(_("Contraseña"));?></label></td><td><input type="password" id="password" /></td>
                     <td class="td_label"><label><?php echo(_("Repita contraseña"));?></label></td><td><input type="password" id="password2" /></td>
                </tr>
                <tr>
                    <td><input  type="submit" name="newTeacher" value="<?php echo(_("Crear"));?>" id="newTeacher" /></td>
                </tr>
            </table>      
        </form>
    </div> 
    
    <div class="gridAfterForm" id="gridTeachers" style="width: 85%; height: 85%;top:370px;"></div>
<div id="labelAux"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridTeachers');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Código usuario"));?>, <?php echo(_("Nombre"));?>, <?php echo(_("Apellidos"));?>, <?php echo(_("Email"));?>, <?php echo(_("DNI"));?>, <?php echo(_("Cambiar contraseña"));?>,<?php echo(_("Nº grupos"));?>,<?php echo(_("Consultar grupos"));?>,<?php echo(_("Eliminar"));?>");
            mygrid.setInitWidths("70,*,*,*,*,90,90,90,80");
            mygrid.setColAlign("center,left,left,left,left,center,center,center,center");
            mygrid.setColTypes("ro,ed,ed,ed,ed,img,ro,img,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,400);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("false,true,true,true,true,false,false,false,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridTeachersAdmin.php",onLoadFunction);
            mygrid.attachEvent("onEditCell", function(stage,rId,cInd,nValue,oValue){
                if (stage == 2){
                    var row = new Array();
                    var cont = 0;
                    var flag;
                    mygrid.forEachCell(rId,function(c){
                        row[cont]=c.getValue();
                        cont++;
                    });

                    var idUser = mygrid.cellById(rId,0).getValue();
                    var dniUser = mygrid.cellById(rId,4).getValue();
                    var input = document.createElement("input");
                    input.setAttribute("type", "hidden");                           
                    input.setAttribute("id", "idHidden");                            
                    input.setAttribute("value", dniUser);
                    document.getElementById("formTeachers").appendChild(input);
                    if(!check_dni($("#idHidden"))){
                        set_tooltip($(".cellSelected"),"<?php echo(_("DNI no válido"));?>");
                        return false;
                    }
                    
                    if(nValue == ""){
                        set_tooltip($('.cellSelected'),"<?php echo(_("No puede estar vacío."));?>");
                        return false;
                    }
                    else{
                        var request = $.ajax({
                          type: "POST",
                          url: "../controller/userController.php",
                          async: false,
                          data: {
                            method:"checkUpdateGridUser", row:JSON.stringify(row), idUser:idUser 
                          }  
                        });
                        request.success(function(request){
                                if($.trim(request) == "1"){
                                    mygrid.cellById(rId, cInd).setValue(nValue); 
                                    mygrid.editStop();
                                    flag= true;
                                }
                                else{ 
                                    set_tooltip($('.cellSelected'),"<?php echo(_("Ya existe un usuario con el mismo DNI."));?>");
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
                <a href="#close" id="closeModal" title="<?php echo(_("Cerrar"));?>" class="close">X</a>
                    <h3><?php echo(_("Grupos del profesor:"));?></h3>
                    <label id="teacherName"></label>
                    <label id="teacherSurnames"></label>
                    <p></p>
                    <input type="hidden" id="idStudent" name="idStudent">                    
                    <div id="gridGestionGrupos" style="width: 100%; height: 70%"></div>
                    <script>
                        var mygrid2 = new dhtmlXGridObject('gridGestionGrupos');
                        mygrid2.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
                        mygrid2.setHeader("<?php echo(_("Grupos"));?>");
                        mygrid2.setInitWidths("*,*,*");
                        mygrid2.setColAlign("center");
                        mygrid2.setColTypes("ro");
                        mygrid2.enableSmartRendering(true);
                        mygrid2.enableAutoHeight(true,200);
                        mygrid2.enableAutoWidth(true);
                        mygrid2.enableTooltips("true");
                        mygrid2.setSizes();
                        mygrid2.setSkin("dhx_skyblue");
                        mygrid2.init();
                    </script>
                    
                    <input  type="button" class="buttonModal" name="cancelar" onclick="window.location = $('#closeModal').attr('href');" value="<?php echo(_("Salir"));?>" id="cancelar" />
            </div>
        </div>
        
        <a href="#openModal2" id="anchorOpenModal2"></a>
        <div id="openModal2" class="modalDialog2">
            <div>
                <a href="#close" id="closeModal2" title="<?php echo(_("Cerrar"));?>" class="close2">X</a>
                    <h3><?php echo(_("Modificar contraseña:"));?></h3>
                    <label class="labelModal"><?php echo(_("Alumno:"));?></label>
                    <label id="studentName2"></label>
                    <p></p>
                    <input type="hidden" id="idStudent2" name="idStudent2">     

                        <label class="labelModal" style="color:#006DB3;"><?php echo(_("Contraseña"));?></label><br>
                        <input type="password" id="changepassword" style="width: 350px;"/><br />
                        <label class="labelModal" style="color:#006DB3;"><?php echo(_("Repita contraseña"));?></label><br />
                        <input type="password" id="changepassword2" style="width: 350px;"/><br /><br>
                    
                     <input  type="submit" class="buttonModal" name="enviar" onclick="saveNewPassword()" value="<?php echo(_("Guardar"));?>" id="saveNewPassword" />
                    <input  type="button" class="buttonModal" name="cancelar" onclick="window.location = $('#closeModal2').attr('href');" value="<?php echo(_("Cancelar"));?>" id="cancelar" />
            </div>
        </div>   
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>


