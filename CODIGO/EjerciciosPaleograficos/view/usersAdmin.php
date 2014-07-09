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
       $('.dhx_combo_option_img').remove();
    });
    
    function validateForm() {
       var empty=false;
       var flag = true;
       $("#formStudents").find(':input').each(function() {              
            if(!empty){
                empty = check_empty(this,"<?php echo(_("Este campo es requerido"));?>");
            }else{
                check_empty(this,"<?php echo(_("Este campo es requerido"));?>");
                flag=false;
            }
        });
        
        var checked_array = combo.getChecked();
        if(checked_array.length == 0){
            set_tooltip($("#combo_zone"),"<?php echo(_("Debe seleccionar al menos un grupo"));?>");
            flag=false;
        }
            
        if(!empty){
            if(!check_dni($("#dnialumno"))){
                set_tooltip($("#dnialumno"),"<?php echo(_("DNI no válido"));?>");
                flag = false;
            }
            if(!check_email($("#emailalumno"))){
                set_tooltip($("#emailalumno"),"<?php echo(_("Formato no válido"));?>");
                flag = false;
            }
            if(!check_names($("#nombrealumno"))){
                set_tooltip($("#nombrealumno"),"<?php echo(_("Formato no válido"));?>");
                flag = false;
            }
            if(!check_names($("#apellidosalumno"))){
                set_tooltip($("#apellidosalumno"),"<?php echo(_("Formato no válido"));?>");
                flag = false;
            }
            if(!check_password("#password")){
                set_tooltip($("#password"),"<?php echo(_("Prueba Debe contener entre 8 - 10 caracteres, al menos un dígito y un alfanumérico"));?>");
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
                method:"newStudent", dnialumno: $("#dnialumno").val(), emailalumno: $("#emailalumno").val(),nombrealumno:$("#nombrealumno").val(),apellidosalumno:$('#apellidosalumno').val(),passwordalumno:$('#password').val(),grupos: JSON.stringify(checked_array),
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "0"){
                        flag= false;
                        set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                    }
                    if($.trim(request) == "1"){
                        set_tooltip_general("<?php echo(_("Se añadió el alumno correctamente."));?>"); 
                    }
                    if($.trim(request) == "2"){
                        flag= false;
                        set_tooltip($("#dnialumno"),"<?php echo(_("Ya existe un usuario con el mismo DNI."));?>");
                    }
            });
        }       
        return flag;
    }
    
    function consultGroups(){
        var rowId = mygrid.getSelectedId();
        var idStudent = mygrid.cellById(rowId, 0).getValue();
        $("#idStudent").val(idStudent);
        $("#studentName").html(mygrid.cellById(rowId, 1).getValue());
        $("#studentSurnames").html(mygrid.cellById(rowId, 2).getValue());
        mygrid2.clearAll();
        mygrid2.loadXML("../controller/gridControllers/gridManageGroups.php?idSearched="+idStudent+"&method=student");
        window.location = $('#anchorOpenModal').attr('href'); 
    }
    
    function deleteStudent(){
        var rowId = mygrid.getSelectedId();
        var idUser = mygrid.cellById(rowId, 0).getValue();
        var nameUser = mygrid.cellById(rowId, 1).getValue();
        var surnameUser = mygrid.cellById(rowId, 2).getValue();


        var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar el alumno "));?>'+nameUser+' ' +surnameUser+'?'}),
                      ok = $('<button />', {text: 'Ok', click: function() {deleteStudentAdmin(idUser);}}),
                      cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});                       
        dialogue( message.add(ok).add(cancel), '<?php echo(_("Confirmación eliminar alumno"))?>'); 
    }
    
    function deleteStudentAdmin(idUser){
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
                        mygrid.updateFromXML("../controller/gridControllers/gridStudentsAdmin.php",false,true); 
                        set_tooltip_general("<?php echo(_("Se eliminó el alumno correctamente."));?>"); 
                    }
                    else{
                        set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                    }
            });
        }
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
                if(index2 == 2){ //Permitir
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
              url: "../controller/userController.php",
              async: false,
              data: {
                method:"updatePermissionsStudent", groups: JSON.stringify(groups),permissions:JSON.stringify(permissions),idStudent: $("#idStudent").val()
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        window.location = $('#closeModal').attr('href');
                        mygrid.updateFromXML("../controller/gridControllers/gridStudentsAdmin.php",false,true);
                        set_tooltip_general("<?php echo(_("Las permisos se actualizaron correctamente"));?>"); 
                    }
                    else{
                        set_tooltip_general_error("<?php echo(_("Ocurrió un error inesperado. Por favor, vuelva a intentarlo más tarde."));?>"); 
                    }
            });
    }
    
    function changePassword(){
        var rowId = mygrid.getSelectedId();
        var idUser = mygrid.cellById(rowId, 5).getAttribute('idStudent');
        
        $("#idStudent2").val(idUser);
        $("#studentName2").html(mygrid.cellById(rowId, 1).getValue());
        $("#studentSurnames2").html(mygrid.cellById(rowId, 2).getValue());
        $("#studentDNI").html(mygrid.cellById(rowId, 4).getValue());
        
        window.location = $('#anchorOpenModal2').attr('href'); 
    }
    
    function saveNewPassword(){
        if(!check_password("#changepassword")){
            set_tooltip($("#changepassword"),"<?php echo(_("Debe contener entre 8-10 caracteres, al menos un dígito y un alfanumérico"));?>");
            return false;
        }else{
            if(!check_password("#changepassword2")){
                set_tooltip($("#changepassword2"),"<?php echo(_("Debe contener entre 8-10 caracteres, al menos un dígito y un alfanumérico"));?>");
                return false;
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
                                set_tooltip_general("<?php echo(_("La contraseña se modificó correctamente."));?>"); 
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
ob_start();
include_once("menu/menu1.php");
?>
    <div class="submenu">
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="usersAdmin.php" style="font-weight: bold"><?php echo(_("Alumnos"));?></a></div>
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="teachersAdmin.php"><?php echo(_("Profesores"));?></a></div>
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="groupsAdmin.php"><?php echo(_("Grupos"));?></a></div>
    </div>

    <div class="formulario"  >
        <form id="formStudents" class="formsAdd" action="usersAdmin.php" method="post" onsubmit="return validateForm()">
           <h2><?php echo(_("Nuevo alumno"));?></h2>
           <table>
               <tr>
                   <td class="td_label"><label><?php echo(_("Nombre"));?></label></td> <td><input type="text" id="nombrealumno">  </td>
                   <td class="td_label"><label><?php echo(_("Apellidos"));?></label></td> <td><input type="text" id="apellidosalumno" /></td>
                   <td class="td_label"><label><?php echo(_("Email"));?></label></td> <td><input type="text" id="emailalumno"/></td>
               </tr>
               <tr>
                   <td class="td_label"><label><?php echo(_("DNI"));?></label></td> <td><input type="text" autocomplete="off" id="dnialumno" /></td>
                   <td class="td_label"><label><?php echo(_("Contraseña"));?></label></td> <td><input type="password" autocomplete="off" id="password" /></td>
                   <td class="td_label" nowrap="nowrap"><label><?php echo(_("Repita contraseña"));?></label></td> <td><input type="password" id="password2" /></td>
               </tr>
               <tr>
                   <td class="td_label"><label><?php echo(_("Grupos"));?></label></td>
                   <td><div id="combo_zone" style="width:200px; height:20px;"></div></td>
               </tr>
               <tr>
                   <td><input  type="submit" name="newStudent" value="<?php echo(_("Crear"));?>" id="newStudent" /></td>
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
    
    <div class="gridAfterForm" id="gridStudents" style="width: 85%; height: 85%;top:370px;"></div>
<div id="labelAux"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridStudents');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Código usuario"));?>, <?php echo(_("Nombre"));?>, <?php echo(_("Apellidos"));?>, <?php echo(_("Email"));?>, <?php echo(_("DNI"));?>, <?php echo(_("Modificar contraseña"));?>,<?php echo(_("Nº grupos"));?>,<?php echo(_("Grupos"));?>,<?php echo(_("Eliminar"));?>");
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
            mygrid.loadXML("../controller/gridControllers/gridStudentsAdmin.php",onLoadFunction);
            mygrid.attachEvent("onEditCell", function(stage,rId,cInd,nValue,oValue){
                if (stage == 2){
                    $('#idHidden').remove();
                    $('#idHiddenEmail').remove();
                    var row = new Array();
                    var cont = 0;
                    var flag;
                    mygrid.forEachCell(rId,function(c){
                        row[cont]=c.getValue();
                        cont++;
                    });

                    var idUser = mygrid.cellById(rId,0).getValue();
                    var dniStudent = mygrid.cellById(rId,4).getValue();
                    var emailStudent = mygrid.cellById(rId,3).getValue();
                    var input = document.createElement("input");
                    input.setAttribute("type", "hidden");                           
                    input.setAttribute("id", "idHidden");                            
                    input.setAttribute("value", dniStudent);
                    document.getElementById("formStudents").appendChild(input);
                    var input2 = document.createElement("input");
                    input2.setAttribute("type", "hidden");                           
                    input2.setAttribute("id", "idHiddenEmail");                            
                    input2.setAttribute("value", emailStudent);
                    document.getElementById("formStudents").appendChild(input2);

                    if(!check_dni($("#idHidden"))){
                        set_tooltip($(".cellSelected"),"<?php echo(_("DNI no válido"));?>");
                        return false;
                    }
                    if(!check_email($("#idHiddenEmail"))){
                        set_tooltip($(".cellSelected"),"<?php echo(_("Formato no válido"));?>");
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
            <div style="width: 550px;">
                <a href="#close" id="closeModal" title="<?php echo(_("Cerrar"));?>" class="close">X</a>
                    <h3><?php echo(_("Gestionar acceso a grupos:"));?></h3>
                    <label class="labelModal"><?php echo(_("Alumno:"));?></label>
                    <label id="studentName"></label>
                    <label id="studentSurnames"></label>
                    <p></p>
                    <input type="hidden" id="idStudent" name="idStudent">                    
                    <div id="gridGestionGrupos" style="width: 100%; height: 100%"></div>
                    <script>
                        var mygrid2 = new dhtmlXGridObject('gridGestionGrupos');
                        mygrid2.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
                        mygrid2.setHeader("<?php echo(_("Grupo"));?>,<?php echo(_("Descripción"));?>, <?php echo(_("Permitir acceso"));?>, <?php echo(_("Denegar acceso"));?>");
                        mygrid2.setInitWidths("150,*,70,70");
                        mygrid2.setColAlign("center,center,center,center");
                        mygrid2.setColTypes("ro,ro,ro,ro");
                        mygrid2.enableSmartRendering(true);
                        mygrid2.enableAutoHeight(true,200);
                        mygrid2.enableAutoWidth(true);
                        mygrid2.enableTooltips("true,true,false,false");
                        mygrid2.setSizes();
                        mygrid2.setSkin("dhx_skyblue");
                        mygrid2.init();
                    </script>
                    <input  type="submit" class="buttonModal" name="enviar" onclick="saveGroupPermissions()" value="<?php echo(_("Guardar"));?>" id="aceptarGestionGrupos" />
                    <input  type="button" class="buttonModal" name="cancelar" onclick="window.location = $('#closeModal').attr('href');" value="<?php echo(_("Cancelar"));?>" id="cancelar" />
                    
            </div>
        </div>   
        
        <a href="#openModal2" id="anchorOpenModal2"></a>
        <div id="openModal2" class="modalDialog2">
            <div>
                <a href="#close" id="closeModal2" title="<?php echo(_("Cerrar"));?>" class="close2">X</a>
                    <h3><?php echo(_("Modificar contraseña"));?></h3>
                    <label class="labelModal"><?php echo(_("Alumno:"));?></label>
                    <label id="studentName2"></label>
                    <label id="studentSurnames2"></label>
                    <p></p>
                    <label class="labelModal"><?php echo(_("DNI:"));?></label>
                    <label id="studentDNI"></label>
                    <p></p>
                    <input type="hidden" id="idStudent2" name="idStudent2">     
    
                    <label class="labelModal" style="color:#006DB3;"><?php echo(_("Contraseña:"));?></label><br>
                    <input type="password" id="changepassword" style="width: 350px;"/><br />
                    <label class="labelModal" style="color:#006DB3;"><?php echo(_("Confirme contraseña:"));?></label><br />
                    <input type="password" id="changepassword2" style="width: 350px;"/><br><br>
                            
                    <input  type="submit" class="buttonModal" name="enviar" onclick="saveNewPassword()" value="<?php echo(_("Guardar"));?>" id="saveNewPassword" />
                    <input  type="button" class="buttonModal" name="cancelar" onclick="window.location = $('#closeModal2').attr('href');" value="<?php echo(_("Cancelar"));?>" id="cancelar" />
                    
            </div>
        </div>
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>


