<?php
	include('../model/acceso_db.php'); // incluimos el archivo de conexión a la Base de Datos
	session_start();
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo(_("Registro UBUPal"));?></title>
	
	<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxGrid/codebase/dhtmlxgrid.css">
	<link rel="stylesheet" href="../public/css/ubupaleo_formstyles.css" />
	<link rel="stylesheet" href="../public/css/ubupaleo_gridstyles.css" />
	<link type="text/css" rel="stylesheet" href="../lib/jquery.qtip/jquery.qtip.css" />
	<script src="../lib/jquery.qtip/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="../public/js/check_inputfields.js"></script>
	<script type="text/javascript" src="../lib/jquery.qtip/jquery.qtip.js"></script>	
    <script  src = "../lib/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
    <script  src = "../lib/dhtmlxGrid/codebase/dhtmlxgrid.js" > </script>
    <script  src = "../lib/dhtmlxGrid/codebase/dhtmlxgridcell.js" ></script>
    <script  src = "../lib/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js"></script>   
    
	<script>
	var mygrid;
	$(document).ready(function(){
		setTimeout(function() {
        var td;
	    	var img;
	    	var grupo;
			var filas =$('.objbox tr').each(function (index){
	    		 $(this).children("td").each(function (index2) {
	    		 	if(index2 == 2){
	    		 		$(this).children("img").bind('click',function($this){
	    		 		    debugger;
	    		 			img = this;
	    		 			var idfila = $(this).attr("id");
	    		 			grupo = mygrid.cellById(idfila-1, 0).getValue();
	    		 			$.ajax({
							  type: "POST",
							  url: "../controller/tooltipInfo_helper.php",
							  async: false,
							  data: {
								  grupo:grupo			  	
							  },
							  dataType:"text",	
							  success: function(request){
							      debugger;
							  	set_tooltipInfo(img,request);
							  }				  
							});
	    		 			  		 		
	    				});

	    			}
	    		});
	    	});
    }, 6000);				
	}); 
		    
	    function validateForm() {
	    	var grupos = new Array();
	    	var cont = 0;
	    	var empty=false;
	    	var flag = true;
	    	
	    	
	    	$("#formRegister").find(':input').each(function() {	        	
	        	if(!empty){
	        		empty = check_empty(this);
	        	}else{
	        		check_empty(this);
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
						set_tooltip($("#textcaptcha"),"<?php echo(_("Texto captcha inválido"));?>");
						flag = false;
					}
					if((json.insertUser) == "repeatUsername"){
						set_tooltip($("#usuario"),"<?php echo(_("Ya existe un usuario con el mismo nombre, introduzca uno diferente por favor"));?>");
						flag = false;
					}
				});
	    	}
		    return flag;
		}
	
					 
		function doInitGrid(){
			mygrid = new dhtmlXGridObject('gridRegistro');
			mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
			mygrid.setHeader("Grupo, Profesor, Información, Seleccionar");
			mygrid.setInitWidths("*,*,100,100");
			mygrid.setColAlign("left,left,center,center");
			mygrid.setColTypes("ro,ro,img,ch");
			mygrid.enableSmartRendering(true);
			mygrid.enableAutoHeight(true,100);
			mygrid.enableAutoWidth(true);
			mygrid.enableTooltips("true,true,false,false");
			mygrid.setSizes();
			mygrid.setSkin("light");
			mygrid.init();					
			mygrid.loadXML("../controller/gridControllers/gridRegistro.php");			
		}
	</script>
    
    
</head>
<body onload="doInitGrid()">
	<div class="formsInicio" style="width: 50%;min-width: 632px;margin-top: 1%">
		<form action="registerOk.php" method="post" onsubmit="return validateForm()" id="formRegister">
			<h2><?php echo(_("Registro UBUPal"));?></h2>
			<p><?php echo(_("Todos los campos del formulario son obligatorios."));?></p>
			<div class="divForm">
				<label><?php echo(_("Apellidos"));?></label></td> <td><input type="text" name="usuario_apellidos" id="apellidos"/>
				<label><?php echo(_("Contraseña"));?></label></td> <td><input type="password" name="usuario_clave"  id="password"/>
				<label><?php echo(_("Repita la contraseña"));?></label></td> <td><input type="password" name="usuario_clave_conf" id="password2"/>
			</div>	
			<div class="divForm">
				<label><?php echo(_("DNI"));?></label></td> <td><input type="text" name="nombre"  id="nombre"/>
				<label><?php echo(_("Usuario"));?></label></td> <td><input type="text" name="usuario_nombre" id="usuario"/>
				<label><?php echo(_("Email"));?></label></td> <td><input type="text" name="usuario_email" placeholder="<?php echo(_("email@ejemplo.com"));?>" id="email"/>
			</div>			   
			<div id="gridRegistro" style="width: 90%; height: 90%"></div>

			<label><?php echo(_("Introduzca el texto de la imagen:"));?></label>
			<table>
				<tr><td><img src="../public/img/captcha.php" id="captcha" alt="img_captcha"/></td>
					<td><input type="text" name="captcha" id="textcaptcha" autocomplete="off"/>
						<a href="#" onclick="document.getElementById('captcha').src='../public/img/captcha.php?'+Math.random();" id="captcha_change" style="margin: 0;"><?php echo(_("Cambiar el texto"));?></a>
					</td>
				</tr>
			</table>
			<input type="submit" name="enviar" value="Enviar" style="display: inline;margin-left: 36%;"/>
			<input type="reset" value="Borrar" style="display:inline;margin-left: 2%;" />			
			<a href="login.php"><?php echo(_("Volver"));?></a>
		</form>
	</div>
</body>
</html>