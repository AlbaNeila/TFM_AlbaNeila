<?php
include('../init.php');
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
  	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  	<meta http-equiv="Cache-control" content="no-cache">
<meta http-equiv="Expires" content="-1">
	<title>UBUPal</title>	
	
	<link href='http://fonts.googleapis.com/css?family=ABeeZee|Exo+2:700' rel='stylesheet' type='text/css'>
	<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxGrid/codebase/dhtmlxgrid.css">
	<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxGrid/codebase/dhtmlx_custom.css">   	
	
	
	<link rel="stylesheet" href="../public/css/ubupaleo_styles.css" />
	<link rel="stylesheet" href="../public/css/ubupaleo_gridstyles.css" />
	<link type="text/css" rel="stylesheet" href="../lib/jquery.qtip/jquery.qtip.css" />
	<link rel="stylesheet" href="../public/css/webfonts/opensans_light/stylesheet.css" type="text/css" charset="utf-8" />
	
	
	<script src="../lib/jquery.qtip/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="../lib/jquery.qtip/jquery.qtip.js"></script>
    <script type="text/javascript" src="../public/js/check_inputfields.js"></script>
    <script  src = "../lib/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
    <script  src = "../lib/dhtmlxGrid/codebase/dhtmlxgrid.js" > </script>
    <script  src = "../lib/dhtmlxGrid/codebase/dhtmlxgridcell.js" ></script>
    <script  src = "../lib/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js"></script> 	
	
	<?php
	if(isset($_SESSION['usuario_nombre'])) {
	if(!empty($GLOBALS['TEMPLATE']['extra_head'])){
		echo $GLOBALS['TEMPLATE']['extra_head'];
	}
	?>
	<script>
		function changeLanguage(language){
		    var sURL = unescape(window.location.pathname);
			var request = $.ajax({
			  type: "POST",
			  url: "../controller/languageController.php",
			  async: false,
			  data: {
			  	lang: language
			  },
			  dataType: "script",	
			  success:function(){
			     location.reload(true);
			  }
			});
		}
	</script>
  </head>
  <body>
  	<div class="divHeader">
  		<table style="margin-left: 2%;text-align: center;">
  			<tr><td><img src="../public/img/ubu.png" alt="escudo_ubu" id="escudo_ubu" style="height: 50px;text-align: center"/></td>
  				<td rowspan="2" style="width:100%;text-align: center"><h1><?php echo(_("Realización de Ejercicios Paleográficos"));?></h1></td>
  			</tr>
  			<tr><td><h3>UBUPal</h3></td></tr>  			
  		</table>
  		
  	</div>

  	<div class="divMenu">
	<?php
	if(!empty($GLOBALS['TEMPLATE']['menu'])){
		echo $GLOBALS['TEMPLATE']['menu'];
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
        <div class="noSession" style="width: 40%;min-width: 510px;">
            <img src="../public/img/ubu.png" style="float:left;height: 50px;margin-top: 4%;">
            <h1><?php echo(_("UBUPal Acceso denegado"));?></h1>
        	<p><?php echo(_("Estás accediendo a una página restringida, para ver su contenido debes estar registrado."));?></p>
        	<a href="login.php"><?php echo(_("Ir a la página de inicio"));?></a>
        </div>
<?php
    }
?> 