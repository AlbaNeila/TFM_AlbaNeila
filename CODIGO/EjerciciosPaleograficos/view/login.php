<?php
	session_start();
	include('../model/acceso_db.php');
	include('../init.php');
?>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo(_("Acceso UBUPaleo"));?></title>
	<link type="text/css" rel="stylesheet" href="../lib/jquery.qtip/jquery.qtip.css" />
	<link rel="stylesheet" href="../public/css/ubupaleo_formstyles.css" />
	<script src="../lib/jquery.qtip/jquery-1.10.2.min.js"></script>	
	<script type="text/javascript" src="../lib/jquery.qtip/jquery.qtip.js"></script>
	<script type="text/javascript" src="../public/js/check_inputfields.js"></script>

	<script>
    function validateForm() {
    	var u = check_empty($("#usuario"));
    	var p = check_empty($("#password"));
		var flag = false;
		
	    if(u || p){
	    	flag= false;
	    }
	    else{
			var request = $.ajax({
			  type: "POST",
			  url: "../controller/loginController.php",
			  async: false,
			  data: {
			  	usuario: $("#usuario").val(), password: $("#password").val()
			  },
			  dataType: "script",	
			});
			request.success(function(request){
					if($.trim(request) == "1"){
						flag= true;
					}
					else{
						flag= false;
						set_tooltip($("#login"),"<?php echo(_("Usuario o contraseña incorrectos"));?>");
					}
			});
	    }
	    return flag;
	}
</script>
	
</head>
<body>
	<?php		
	    if(empty($_SESSION['usuario_nombre'])) { // comprobamos que las variables de sesión estén vacías        
	?>			
	<div class="formsInicio" style="width:22%;min-width:278px;">
        <form action="templates/index.php" method="post" onsubmit="return validateForm()">
        	<h2><?php echo(_("Acceso UBUPaleo"));?></h2>
        	<label><?php echo(_("Usuario:"));?></label>
			<input  type="text" name="usuario_nombre" id="usuario" />
        	<label ><?php echo(_("Contraseña:"));?></label>
        	<input  type="password" name="usuario_clave" id="password"/>
            <input  type="submit" name="enviar" value="<?php echo(_("Entrar"));?>" id="login" />
            <a href="register.php"><?php echo(_("Registro de alumnos"));?></a> <br />
            <a href="recuperar_contrasena.php"><?php echo(_("¿Ha extraviado la contraseña?"));?></a>          
        </form>                   
    </div>
	<?php
	    }else {
			header("Location: templates/index.php");
	    }
	?>
</body>
</html>