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
       $("#formStudents").find(':input').each(function() {              
            if(!empty){
                empty = check_empty(this);
            }else{
                check_empty(this);
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
                method:"newStudent", dnialumno: $("#dnialumno").val(), emailalumno: $("#emailalumno").val(),nombrealumno:$("#nombrealumno").val(),apellidosalumno:$('#apellidosalumno').val(),passwordalumno:$('#passwordalumno').val(),grupos: JSON.stringify(checked_array),
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
        mygrid2.clearAll();
        mygrid2.loadXML("../controller/gridControllers/gridManageGroups.php?idSearched="+idStudent+"&method=student");
        window.location = $('#anchorOpenModal').attr('href'); 
    }
    
    function deleteStudent(){
        var rowId = mygrid.getSelectedId();
        var idUser = mygrid.cellById(rowId, 0).getValue();


        var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar el alumno?"));?>'}),
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
                if(index2 == 1){ //Permitir
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
                    }
                    else{
                        alert("error");
                    }
            });
    }
    
    function changePassword(){
        var rowId = mygrid.getSelectedId();
        var idUser = mygrid.cellById(rowId, 5).getAttribute('idStudent');
        
        $("#idStudent2").val(idUser);
        $("#studentName2").html(mygrid.cellById(rowId, 1).getValue());
        
        window.location = $('#anchorOpenModal2').attr('href'); 
    }
    
    function saveNewPassword(){
        debugger;
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
        <div class="submenuitem"><a href="usersAdmin.php" style="font-weight: bold"><?php echo(_("Alumnos"));?></a></div>
        <div class="submenuitem"><a href="teachersAdmin.php"><?php echo(_("Profesores"));?></a></div>
        <div class="submenuitem"><a href="groupsAdmin.php"><?php echo(_("Grupos"));?></a></div>
    </div>

    <div class="formulario"  >
        <form id="formStudents" action="usersAdmin.php" method="post" onsubmit="return validateForm()">
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
                <input type="password" id="password" />
                <label><?php echo(_("Repita contraseña"));?></label>
                <input type="password" id="password2" />
            </div>
            <div class="blockformulario">
                <label><?php echo(_("Email"));?></label>
                <input type="text" id="emailalumno"/>
                <label><?php echo(_("Grupos"));?></label>
                <div id="combo_zone" style="width:200px; height:20px;"></div>
                <script>
                    window.dhx_globalImgPath="../lib/dhtmlxCombo/codebase/imgs/";
                    var combo = new dhtmlXCombo("combo_zone","comboGroups",200,'checkbox');
                    dhtmlx.skin = 'dhx_skyblue';
                    combo.enableOptionAutoWidth(true);
                    combo.enableOptionAutoHeight(true);
                    combo.enableOptionAutoPositioning();
                    combo.loadXML("../controller/comboControllers/comboGroups.php?method=admin"); 
                </script>
            </div>
            
            <div class="buttonformulario">
            <input  type="submit" name="newStudent" value="<?php echo(_("Crear"));?>" id="newStudent" />
            </div>
            </fieldset>        
        </form>
    </div> 
    
    <div class="gridAfterForm" id="gridStudents" style="width: 85%; height: 85%"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridStudents');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Código usuario"));?>, <?php echo(_("Nombre"));?>, <?php echo(_("Apellidos"));?>, <?php echo(_("Email"));?>, <?php echo(_("DNI"));?>, <?php echo(_("Modificar contraseña"));?>,<?php echo(_("Nº grupos"));?>,<?php echo(_("Consultar grupos"));?>,<?php echo(_("Eliminar"));?>");
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
            mygrid.loadXML("../controller/gridControllers/gridStudentsAdmin.php");
            mygrid.attachEvent("onEditCell", function(stage,rId,cInd,nValue,oValue){
                if (stage == 2){
                    var row = new Array();
                    var cont = 0;
                    var flag;
                    mygrid.forEachCell(rId,function(c){
                        row[cont]=c.getValue();
                        cont++;
                    });
                    //row[1]=nValue;

                    var idUser = mygrid.cellById(rId,0).getValue();
                    var dniStudent = mygrid.cellById(rId,4).getValue();
                    var input = document.createElement("input");
                    input.setAttribute("type", "hidden");                           
                    input.setAttribute("id", "idHidden");                            
                    input.setAttribute("value", dniStudent);
                    document.getElementById("formStudents").appendChild(input);
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
                    <h3><?php echo(_("Gestionar acceso a grupos:"));?></h3>
                    <label><?php echo(_("Alumno:"));?></label>
                    <label id="studentName"></label>
                    <input type="hidden" id="idStudent" name="idStudent">                    
                    <div id="gridGestionGrupos" style="width: 100%; height: 100%"></div>
                    <script>
                        var mygrid2 = new dhtmlXGridObject('gridGestionGrupos');
                        mygrid2.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
                        mygrid2.setHeader("<?php echo(_("Grupo"));?>, <?php echo(_("Permitir acceso"));?>, <?php echo(_("Denegar acceso"));?>");
                        mygrid2.setInitWidths("*,*,*");
                        mygrid2.setColAlign("center,center,center");
                        mygrid2.setColTypes("ro,ro,ro");
                        mygrid2.enableSmartRendering(true);
                        mygrid2.enableAutoHeight(true,200);
                        mygrid2.enableAutoWidth(true);
                        mygrid2.enableTooltips("true,false,false");
                        mygrid2.setSizes();
                        mygrid2.setSkin("dhx_skyblue");
                        mygrid2.init();
                    </script>
                    
                    <input  type="button" name="cancelar" onclick="window.location = $('#closeModal').attr('href');" value="<?php echo(_("Cancelar"));?>" id="cancelar" />
                    <input  type="submit" name="enviar" onclick="saveGroupPermissions()" value="<?php echo(_("Guardar"));?>" id="aceptarGestionGrupos" />
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


