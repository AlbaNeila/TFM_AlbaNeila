<?php
    session_start();
    include('acceso_db.php');
?> 
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Colecciones</title>
</head>
<body>
	<?php
	    if(isset($_SESSION['usuario_nombre'])) {
	?>
	        Bienvenido: <a href="perfil.php?id=<?=$_SESSION['usuario_id']?>"><strong><?=$_SESSION['usuario_nombre']?></strong></a><br />
	        <a href="logout.php">Cerrar Sesión</a>
	<?php
	    }else {

header("Location: login.php");
	    }
	?> 
</body>
</html>