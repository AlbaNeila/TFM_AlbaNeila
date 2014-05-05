<?php
session_start();
include('../../init.php');
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
  	<meta content="text/html; charset=utf-8"/>
	<title>UBUPaleo</title>
	<link href='http://fonts.googleapis.com/css?family=Exo+2:800italic' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../public/css/ubupaleo_styles.css" />
	<link rel="stylesheet" href="../../public/css/ubupaleo_formstyles.css" />
	<script src="../../lib/jquery.qtip/jquery-1.10.2.min.js"></script>	
	<?php
	if(isset($_SESSION['usuario_nombre'])) {
	if(!empty($GLOBALS['TEMPLATE']['extra_head'])){
		echo $GLOBALS['TEMPLATE']['extra_head'];
	}
	?>
	<script>
		function changeLanguage(language){
			debugger;
			var request = $.ajax({
			  type: "GET",
			  url: "index.php",
			  async: false,
			  data: {
			  	lang: language
			  },
			  dataType: "script",	
			});
		}
	</script>
  </head>
  <body>
  	<div class="divHeader">
  		<table style="margin-left: 2%;text-align: center;">
  			<tr><td><img src="../../public/img/ubu.png" alt="escudo_ubu" id="escudo_ubu" style="height: 50px;text-align: center"/></td>
  				<td rowspan="2" style="width:100%;text-align: center"><h1><?php echo(_("Realización de Ejercicios Paleográficos"));?></h1></td>
  			</tr>
  			<tr><td><h3>UBUPaleo</h3></td></tr>  			
  		</table>
  		
  	</div>

  	<div class="divMenu">
  	<?php
  		$tipoUsuario = $_SESSION['usuario_tipo'];
		if($tipoUsuario == 'ALUMNO'){
  	?>
  		<ul id="menu">
  			<li><a href="#" style="text-decoration:underline"><?php echo(_("Colecciones"));?></a></li>
  			<li><a href="#"><?php echo(_("Grupos"));?></a></li>
  			<li><a href="#"><?php echo(_("Estadísticas"));?></a></li>
  			<li><a href="#"><?php echo(_("Ayuda"));?></a></li>
  		</ul>
  		<ul id="menu2">
  			<li><a href="../../controller/logout.php">Salir</a></li>
  			<li><label><?php echo(_("Bienvenid@:"));?>  <?php echo($_SESSION['usuario_nombre']); ?></label></li>
  			<li><img src="../../public/img/english.png" style="height: 25px;padding-top: 2px;padding-left: 4px;" title="<?php echo(_("Inglés"));?>" id="en_US" onclick="changeLanguage($(this).attr('id'))" /></li>
  			<li><img src="../../public/img/spanish.png" style="height: 25px;padding-top: 2px;padding-right: 4px;" title="<?php echo(_("Español"));?>" id="es_ES" onclick="changeLanguage($(this).attr('id'))" /></li>  			
  		</ul>
  		
  		
  		
  		<?php
		}elseif($tipoUsuario == 'PROFESOR'){
  		?>
  		<ul id="menu">
  			<li><a href="#" style="text-decoration:underline"><?php echo(_("Colecciones"));?></a></li>
  			<li><a href="#"><?php echo(_("Grupos"));?></a></li>
  			<li><a href="#"><?php echo(_("Ejercicios"));?></a></li>
  			<li><a href="#"><?php echo(_("Estadísticas"));?></a></li>
  			<li><a href="#"><?php echo(_("Ayuda"));?></a></li>
  		</ul>
  		<ul id="menu2">
  			<li><a href="../../controller/logout.php">Salir</a></li>
  			<li><label><?php echo(_("Bienvenid@:"));?>  <?php echo($_SESSION['usuario_nombre']); ?></label></li>
  			<li><img src="../../public/img/english.png" style="height: 25px;padding-top: 2px;padding-left: 4px;" title="<?php echo(_("Inglés"));?>" id="en_US" onclick="changeLanguage($(this).attr('id'))" /></li>
  			<li><img src="../../public/img/spanish.png" style="height: 25px;padding-top: 2px;padding-right: 4px;" title="<?php echo(_("Español"));?>" id="es_ES" onclick="changeLanguage($(this).attr('id'))" /></li>  			
  		</ul>
  		<?php
  		}else{
  		?>
  		<ul id="menu">
  			<li><a href="#" style="text-decoration:underline"><?php echo(_("Usuarios"));?></a></li>
  			<li><a href="#"><?php echo(_("Colecciones"));?></a></li>
  			<li><a href="#"><?php echo(_("Ejercicios"));?></a></li>
  			<li><a href="#"><?php echo(_("Estadísticas"));?></a></li>
  		</ul>
  		<ul id="menu2">
  			<li><a href="../../controller/logout.php">Salir</a></li>
  			<li><label><?php echo(_("Bienvenid@:"));?>  <?php echo($_SESSION['usuario_nombre']); ?></label></li>
  			<li><img src="../../public/img/english.png" style="height: 25px;padding-top: 2px;padding-left: 4px;" title="<?php echo(_("Inglés"));?>" id="en_US" onclick="changeLanguage($(this).attr('id'))" /></li>
  			<li><img src="../../public/img/spanish.png" style="height: 25px;padding-top: 2px;padding-right: 4px;" title="<?php echo(_("Español"));?>" id="es_ES" onclick="changeLanguage($(this).attr('id'))" /></li>  			
  		</ul>
  		<?php
		}
  		?>
  	</div>

  	<div class="divContent">
	<?php
	if(!empty($GLOBALS['TEMPLATE']['content'])){
		echo $GLOBALS['TEMPLATE']['content'];
	}
	?>
  	</div>
		
  </body>
</html>
<?php
    }else {
?>
        <div class="formsInicio" style="width: 30%;min-width: 385px;">
        	<form action="#">
	        	<p><?php echo(_("Estás accediendo a una página restringida, para ver su contenido debes estar registrado."));?></p>
	        	<a href="../login.php"><?php echo(_("Ir a la página de inicio"));?></a>
        	</form>
        </div>
<?php
    }
?> 