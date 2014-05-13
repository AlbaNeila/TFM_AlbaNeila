<?php
session_start();
ob_start();
?>
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
  			<li><a href="../controller/logout.php">Salir</a></li>
  			<li><label><?php echo(_("Bienvenid@:"));?>  <?php echo($_SESSION['usuario_nombre']); ?></label></li>
  			<li><img src="../public/img/english.png" style="height: 25px;padding-top: 2px;padding-left: 4px;" title="<?php echo(_("Inglés"));?>" id="en_US" onclick="changeLanguage($(this).attr('id'))" /></li>
  			<li><img src="../public/img/spanish.png" style="height: 25px;padding-top: 2px;padding-right: 4px;" title="<?php echo(_("Español"));?>" id="es_ES" onclick="changeLanguage($(this).attr('id'))" /></li>  			
  		</ul> 		
  		<?php
		}elseif($tipoUsuario == 'PROFESOR'){
  		?>
  		<ul id="menu">
  			<li><a href="#"><?php echo(_("Colecciones"));?></a></li>
  			<li><a href="groupTeacher.php" ><?php echo(_("Grupos"));?></a></li>
  			<li><a href="#"><?php echo(_("Ejercicios"));?></a></li>
  			<li><a href="#" class="active"><?php echo(_("Estadísticas"));?></a></li>
  			<li><a href="#"><?php echo(_("Ayuda"));?></a></li>
  		</ul>
  		<ul id="menu2">
  			<li><a href="/../controller/logout.php">Salir</a></li>
  			<li><label><?php echo(_("Bienvenid@:"));?>  <?php echo($_SESSION['usuario_nombre']); ?></label></li>
  			<li><img src="../public/img/english.png" style="height: 25px;padding-top: 2px;padding-left: 4px;" title="<?php echo(_("Inglés"));?>" id="en_US" onclick="changeLanguage($(this).attr('id'))" /></li>
  			<li><img src="../public/img/spanish.png" style="height: 25px;padding-top: 2px;padding-right: 4px;" title="<?php echo(_("Español"));?>" id="es_ES" onclick="changeLanguage($(this).attr('id'))" /></li>  			
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
  			<li><a href="../controller/logout.php">Salir</a></li>
  			<li><label><?php echo(_("Bienvenid@:"));?>  <?php echo($_SESSION['usuario_nombre']); ?></label></li>
  			<li><img src="../public/img/english.png" style="height: 25px;padding-top: 2px;padding-left: 4px;" title="<?php echo(_("Inglés"));?>" id="en_US" onclick="changeLanguage($(this).attr('id'))" /></li>
  			<li><img src="../public/img/spanish.png" style="height: 25px;padding-top: 2px;padding-right: 4px;" title="<?php echo(_("Español"));?>" id="es_ES" onclick="changeLanguage($(this).attr('id'))" /></li>  			
  		</ul>
  		<?php
		}
$GLOBALS['TEMPLATE']['menu']= ob_get_clean();
include_once('template.php');
  		?>


