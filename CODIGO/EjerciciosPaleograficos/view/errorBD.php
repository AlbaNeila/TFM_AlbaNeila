
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo(_("Registro UBUPal"));?></title>
	
	<link rel="stylesheet" href="../public/css/ubupaleo_formregister.css" />
	<link rel="stylesheet" href="../public/css/webfonts/opensans_light/stylesheet.css" type="text/css" charset="utf-8" />
	<script src="../lib/jquery.qtip/jquery-1.10.2.min.js"></script>
	  
    
</head>
<body>
	<div class="formsInicio" style="width: 30%;min-width: 379px;">
		<form action="login.php" method="post" id="formRegisterOk">
			<img src="../public/img/ubu.png" style="float:left;height: 50px;margin-top: -1%;">
            <h1><?php echo(_("Registro correcto"));?></h1>
			<p><?php echo(_("¡Bienvenido!"));?></p>
			<p><?php echo(_("Su cuenta ha sido creada con éxito."));?></p>
			<p><?php echo(_("Ya puede acceder a la aplicación UBUPal. Cuando el profesor acepte sus solicitudes tendrá acceso a los grupos seleccionados."));?></p>
			<p><?php echo(_("Muchas gracias."));?></p>
			<input class="buttonInicio" type="submit" name="volver" value="<?php echo(_("Volver"));?>" style="display: inline;"/>
		</form>
	</div>
</body>
</html>


			
			