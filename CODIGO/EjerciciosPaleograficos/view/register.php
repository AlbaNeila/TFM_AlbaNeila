<?php
    session_start();
include("../model/persistence/acceso_db.php"); // incluimos el archivo de conexión a la Base de Datos
	include('../init.php');	
?>
<!doctype html>
<html lang="en">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php echo(_("Registro UBUPal"));?></title>
	
	<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxGrid/codebase/dhtmlxgrid.css">
	<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxCombo/codebase/dhtmlx_custom.css">  
	<link rel="stylesheet" href="../public/css/ubupaleo_formregister.css" />
	<link rel="stylesheet" href="../public/css/ubupaleo_gridstyles.css" />
	<link type="text/css" rel="stylesheet" href="../lib/jquery.qtip/jquery.qtip.css" />
	<link rel="stylesheet" href="../public/css/webfonts/opensans_light/stylesheet.css" type="text/css" charset="utf-8" />
	<script src="../lib/jquery.qtip/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="../public/js/check_inputfields.js"></script>
	<script type="text/javascript" src="../lib/jquery.qtip/jquery.qtip.js"></script>	
    <script  src = "../lib/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
    <script  src = "../lib/dhtmlxGrid/codebase/dhtmlxgrid.js" > </script>
    <script  src = "../lib/dhtmlxGrid/codebase/dhtmlxgridcell.js" ></script>
    <script  src = "../lib/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js"></script>   
    
	<script>
	var mygrid;
	
	function consultInfoGroup(){
	    var rowId = mygrid.getSelectedId();
        var idGroup = mygrid.cellById(rowId, 2).getAttribute("idGroup");
        var cell=$('td.cellselected');
	    $.ajax({
          type: "POST",
          url: "../controller/tooltipInfoController.php",
          async: false,
          data: {
              grupo:idGroup               
          },
          dataType:"text",  
          success: function(request){
            set_tooltip_left(cell,request);
          }               
        });
	}
		    
	    function validateForm() {
	    	var grupos = new Array();
	    	var cont = 0;
	    	var empty=false;
	    	var flag = true;

	    	$("#formRegister").find(':input').each(function() {	        	
	        	if(!empty){
	        		empty = check_empty(this,"<?php echo(_("Este campo es requerido"));?>");
	        	}else{
	        		check_empty(this,"<?php echo(_("Este campo es requerido"));?>");
	        		flag=false;
	        	}
	        });
	    	
	    	if(!empty){
		    	if(!check_dni($("#usuario"))){
		    		set_tooltip($("#usuario"),"<?php echo(_("DNI no válido"));?>");
		    		flag = false;
		    	}
		    	if(!check_email($("#email"))){
		    		set_tooltip($("#email"),"<?php echo(_("Formato no válido"));?>");
		    		flag = false;
		    	}
		    	if(!check_names($("#nombre"))){
		    		set_tooltip($("#nombre"),"<?php echo(_("Formato no válido"));?>");
		    		flag = false;
		    	}
		    	if(!check_names($("#apellidos"))){
		    		set_tooltip($("#apellidos"),"<?php echo(_("Formato no válido"));?>");
		    		flag = false;
		    	}
		    	if(!check_password("#password")){
		    		set_tooltip($("#password"),"<?php echo(_("Debe contener entre 8-10 caracteres, al menos un dígito y un alfanumérico"));?>");
		    		flag = false;
		    	}
		    	else{
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
		    	
		    	mygrid.forEachRow(function(id){
				    mygrid.forEachCell(id,function(c){
				       if(c.isChecked()){
				       	 grupos[cont] = mygrid.cellById(id, 0).getValue();
				       	 cont++;
				       }
				    });
				});
				if(grupos.length == 0){
					set_tooltip($("#gridRegistro"),"<?php echo(_("Debe seleccionar al menos un grupo"));?>");
					flag = false;
				}
	    	}
	    	
	    	if(flag){				
		    	var request = $.ajax({
				  type: "POST",
				  url: "../controller/registerController.php",
				  async: false,
				  data: {
					  captcha: $("#textcaptcha").val(),
					  nombre:$("#nombre").val(),
					  usuario_apellidos:$("#apellidos").val(),
					  usuario_nombre:$("#usuario").val(),
					  usuario_email:$("#email").val(),
					  usuario_clave:$("#password").val(),
					  grupos:JSON.stringify(grupos)				  	
				  },
				  dataType:"json",					  
				});
				request.success(function(json){
					if((json.captcha) == "captcha"){
						set_tooltip($("#textcaptcha"),"<?php echo(_("El texto introducido no coincide"));?>");
						flag = false;
					}
					if((json.insertUser) == "repeatUsername"){
						set_tooltip($("#usuario"),"<?php echo(_("Ya existe un usuario con el mismo DNI, introduzca uno diferente por favor"));?>");
						flag = false;
					}
				});
	    	}
		    return flag;
		}
	
         onLoadFunction = function onLoadFunction(){
                if(mygrid.getRowsNum()==0){
                    var label = document.createElement("label");
                label.setAttribute("class", "gridAfterForm");                           
                label.setAttribute("id", "noRecords");
                label.setAttribute("style", "width: 75%; height: 90%;top:420px;text-align: center;");                            
                $(label).text("<?php echo(_("- No se encontraron resultados -"));?>");
                document.getElementById("labelAux").appendChild(label);
            }else{
               $("#noRecords").remove();
            }
        }
					 
		function doInitGrid(){
			mygrid = new dhtmlXGridObject('gridRegistro');
			mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
			mygrid.setHeader("<?php echo(_("Grupo"));?>, <?php echo(_("Profesor"));?>, <?php echo(_("Información"));?>, <?php echo(_("Seleccionar"));?>");
			mygrid.setInitWidths("*,*,100,100");
			mygrid.setColAlign("left,left,center,center");
			mygrid.setColTypes("ro,ro,img,ch");
			mygrid.enableSmartRendering(true);
			mygrid.enableAutoHeight(true,250);
			mygrid.enableAutoWidth(true);
			mygrid.enableTooltips("true,true,false,false");
			mygrid.setSizes();
			mygrid.setSkin("dhx_skyblue");
			mygrid.init();					
			mygrid.loadXML("../controller/gridControllers/gridRegistro.php",onLoadFunction);			
		}
		
	
	</script>
    
    
</head>
<body onload="doInitGrid()">
	<div class="formsInicio" style="width: 45%;min-width: 569px;margin-top: 1%">
		<form action="registerOk.php" method="post" onsubmit="return validateForm()" id="formRegister">
			<img src="../public/img/ubu.png" style="float:left;height: 50px;margin-top: -1%;">
            <h1><?php echo(_("Registro UBUPal"));?></h1>
			<p><?php echo(_("Todos los campos del formulario son obligatorios."));?></p>
			<div class="divForm">
				<label><?php echo(_("Apellidos"));?></label></td> <td><input tabindex="2" type="text" name="usuario_apellidos" id="apellidos"/></td>
				<label><?php echo(_("Email"));?></label></td> <td><input tabindex="4" type="text" autocomplete="off" name="usuario_email" id="email"/></td>
				<label><?php echo(_("Confirme contraseña"));?></label></td> <td><input tabindex="6" autocomplete="off" type="password" name="usuario_clave_conf" id="password2"/></td>
			</div>	
			<div class="divForm">
				<label><?php echo(_("Nombre"));?></label></td> <td><input tabindex="1" type="text" name="nombre"  id="nombre"/></td>
				<label><?php echo(_("DNI"));?></label></td> <td><input tabindex="3" type="text" name="usuario_nombre" id="usuario"/></td>
				<label><?php echo(_("Contraseña"));?></label></td> <td><input tabindex="5" type="password" name="usuario_clave"  id="password"/></td>
			</div>	
			<label><?php echo(_("Grupo"));?></label>		   
			<div id="gridRegistro" style="width: 96%; height: 100%"></div>
<div id="labelAux"></div>

			<p><?php echo(_("Introduzca el texto de la imagen:"));?></p>
			<table>
				<tr><td><img src="../public/img/captcha.php" id="captcha" alt="img_captcha"/></td>
					<td><input tabindex="7" type="text" name="captcha" id="textcaptcha" autocomplete="off"/>
						<a href="#" onclick="document.getElementById('captcha').src='../public/img/captcha.php?'+Math.random();" id="captcha_change" style="margin: 0;"><?php echo(_("Cambiar el texto"));?></a>
					</td>
				</tr>
			</table>
			<input type="submit" class="buttonInicio" name="enviar" value="<?php echo(_("Enviar"));?>" style="display: inline;margin-left: 36%;"/>
			<input type="reset" class="buttonInicio" value="<?php echo(_("Borrar"));?>" style="display:inline;margin-left: 2%;" />			
			<a href="login.php"><?php echo(_("Volver"));?></a>
		</form>
	</div>
</body>
</html>