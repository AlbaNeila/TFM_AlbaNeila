<?php
	include('../model/acceso_db.php'); // incluimos el archivo de conexión a la Base de Datos
	include('../init.php');
	session_start();
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo(_("UBUPal"));?></title>
	
	<link rel="stylesheet" href="../public/css/ubupaleo_formregister.css" />
	<link rel="stylesheet" href="../public/css/webfonts/opensans_light/stylesheet.css" type="text/css" charset="utf-8" />
	<script src="../lib/jquery.qtip/jquery-1.10.2.min.js"></script>
	  
    
</head>
<body>
	<div class="formsInicio" style="width: 33%;min-width: 379px;">
		<form action="login.php" method="post" id="formRegisterOk">
            <h1><?php echo(_("Su contraseña ha sido enviada correctamente"));?></h1>
			<p><?php echo(_("Por favor revise su correo electrónico para averiguar su nueva contraseña."));?></p>
			<p><?php echo(_("Muchas gracias."));?></p>
			<input class="buttonInicio" type="submit" name="volver" value="<?php echo(_("Volver"));?>" style="display: inline;"/>
		</form>
	</div>
</body>
</html>


			
			