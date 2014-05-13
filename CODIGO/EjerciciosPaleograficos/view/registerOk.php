<?php
	include('../model/acceso_db.php'); // incluimos el archivo de conexión a la Base de Datos
	session_start();
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo(_("Registro UBUPal"));?></title>
	
	<link rel="stylesheet" href="../public/css/ubupaleo_formstyles.css" />
	<script src="../lib/jquery.qtip/jquery-1.10.2.min.js"></script>
	  
    
</head>
<body>
	<div class="formsInicio" style="width: 35%;min-width: 443px;">
		<form action="login.php" method="post" id="formRegisterOk">
			<h2><?php echo(_("Registro correcto"));?></h2>
			<p><?php echo(_("¡Bienvenido!"));?></p>
			<p><?php echo(_("Su cuenta ha sido creada con éxito."));?></p>
			<p><?php echo(_("Ya puede acceder a la aplicación UBUPal. Cuando el profesor acepte sus solicitudes tendrá acceso a los grupos seleccionados."));?></p>
			<p><?php echo(_("Muchas gracias."));?></p>
			<input type="submit" name="volver" value="<?php echo(_("Volver"));?>" style="display: inline;"/>
		</form>
	</div>
</body>
</html>


			
			