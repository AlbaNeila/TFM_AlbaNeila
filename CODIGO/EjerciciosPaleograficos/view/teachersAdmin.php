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
       var empty=false;
       var flag = true;
       $("#formTeachers").find(':input').each(function() {              
            if(!empty){
                empty = check_empty(this);
            }else{
                check_empty(this);
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
                        alert("error");
                    }
                    if($.trim(request) == "1"){
                        flag= true;
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
        mygrid2.clearAll();
        mygrid2.loadXML("../controller/gridControllers/gridTeacherGroups.php?idSearched="+idTeacher);
        window.location = $('#anchorOpenModal').attr('href'); 
    }
    
    function deleteTeacher(){
        var rowId = mygrid.getSelectedId();
        var idUser = mygrid.cellById(rowId, 0).getValue();


        var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar el profesor?"));?>'}),
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
                    }
                    else{
                        alert("error");
                    }
            });
        }
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
                            }
                            else{
                                alert("error");
                            }
                    });
                }
            }
        }
    }
    
</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('/menu/menu1.php');
ob_start();
?>
    <div class="submenu">
        <div class="submenuitem"><a href="usersAdmin.php" ><?php echo(_("Alumnos"));?></a></div>
        <div class="submenuitem"><a href="teachersAdmin.php"  style="font-weight: bold"><?php echo(_("Profesores"));?></a></div>
        <div class="submenuitem"><a href="groupsAdmin.php"><?php echo(_("Grupos"));?></a></div>
    </div>

    <div class="formulario"  >
        <form id="formTeachers" action="teachersAdmin.php" method="post" onsubmit="return validateForm()">
            <fieldset>
                
            <legend><h3><?php echo(_("Nuevo profesor"));?></h3></legend>
            <div class="blockformulario">
                <label><?php echo(_("Nombre"));?></label>
                <input type="text" id="nombreprofesor">              
                <label><?php echo(_("DNI"));?></label>
                <input type="text" id="dniprofesor" />
            </div>
            <div class="blockformulario">
                <label><?php echo(_("Apellidos"));?></label>
                <input type="text" id="apellidosprofesor" />
                <label><?php echo(_("Contraseña"));?></label>
                <input type="text" id="password" />
                <label><?php echo(_("Repita contraseña"));?></label>
                <input type="password" id="password2" />
            </div>
            <div class="blockformulario">
                <label><?php echo(_("Email"));?></label>
                <input type="text" id="emailprofesor"/>                
            </div>
            
            <div class="buttonformulario">
            <input  type="submit" name="newTeacher" value="<?php echo(_("Crear"));?>" id="newTeacher" />
            </div>
            </fieldset>        
        </form>
    </div> 
    
    <div class="gridAfterForm" id="gridTeachers" style="width: 85%; height: 85%"></div>
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
            mygrid.loadXML("../controller/gridControllers/gridTeachersAdmin.php");
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
                <a href="#close" id="closeModal" title="Close" class="close">X</a>
                    <h3><?php echo(_("Grupos del profesor:"));?></h3>
                    <label id="teacherName"></label>
                    <input type="hidden" id="idStudent" name="idStudent">                    
                    <div id="gridGestionGrupos" style="width: 70%; height: 70%"></div>
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
                    
                    <input  type="button" name="cancelar" onclick="window.location = $('#closeModal').attr('href');" value="<?php echo(_("Salir"));?>" id="cancelar" />
            </div>
        </div>
        
        <a href="#openModal2" id="anchorOpenModal2"></a>
        <div id="openModal2" class="modalDialog2">
            <div>
                <a href="#close" id="closeModal2" title="Close" class="close2">X</a>
                    <h3><?php echo(_("Modificar contraseña:"));?></h3>
                    <label><?php echo(_("Alumno:"));?></label>
                    <label id="studentName2"></label>
                    <input type="hidden" id="idStudent2" name="idStudent2">     

                        <label><?php echo(_("Contraseña"));?></label>
                        <input type="password" id="changepassword" />
                        <label><?php echo(_("Repita contraseña"));?></label>
                        <input type="password" id="changepassword2" />
                    
                    <input  type="button" name="cancelar" onclick="window.location = $('#closeModal2').attr('href');" value="<?php echo(_("Cancelar"));?>" id="cancelar" />
                    <input  type="submit" name="enviar" onclick="saveNewPassword()" value="<?php echo(_("Guardar"));?>" id="saveNewPassword" />
            </div>
        </div>   
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>


