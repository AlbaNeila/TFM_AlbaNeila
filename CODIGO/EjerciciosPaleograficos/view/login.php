<?php
ob_start();
	session_start();
include("../model/persistence/acceso_db.php");
	include('../init.php');
    $defaultLang="es_ES";
    $flag=0;
    if(isset($_SESSION['lang'])){
        if($defaultLang != $_SESSION['lang']){
            $defaultLang ="en_US";
            $flag=1;
        }
    }
?>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo(_("Acceso UBUPal"));?></title>
	<link type="text/css" rel="stylesheet" href="../lib/jquery.qtip/jquery.qtip.css" />
	<link rel="stylesheet" href="../public/css/ubupaleo_forminicio.css" />
	<link rel="stylesheet" href="../public/css/webfonts/opensans_light/stylesheet.css" type="text/css" charset="utf-8" />
	<script src="../lib/jquery.qtip/jquery-1.10.2.min.js"></script>	
	<script type="text/javascript" src="../lib/jquery.qtip/jquery.qtip.js"></script>
	<script type="text/javascript" src="../public/js/check_inputfields.js"></script>

	<script>	
	$(document).ready(function(){
	   var flag=<?php echo $flag;?>;
	   if(flag!=0){
	       $('#languageSelect option:eq(1)').prop('selected', true);
	   }
	    
	});
	
    function validateForm() {
    	var u = check_empty($("#usuario"),"<?php echo(_("Este campo es requerido"));?>");
    	var p = check_empty($("#password"),"<?php echo(_("Este campo es requerido"));?>");
		var flag = false;
		
	    if(u || p){
	    	flag= false;
	    }
	    else{
			var request = $.ajax({
			  type: "POST",
			  url: "../controller/loginController.php?method=checkLogin",
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
					if($.trim(request) == "0"){
						flag= false;
						set_tooltip($("#login"),"<?php echo(_("Usuario o contraseña incorrectos"));?>");
					}
			});
	    }
	    return flag;
	}
	
	function changeLang(){
	    var e = document.getElementById("languageSelect");
        var language = e.options[e.selectedIndex].value;
	    if(language==1){
	        changeLanguage('en_US');
	    }else{
	        changeLanguage('es_ES');
	    }
	}
	
       function changeLanguage(language){
            var request = $.ajax({
              type: "POST",
              url: "../controller/languageController.php",
              async: false,
              data: {
                lang: language
              },
              dataType: "script"
              });   
              request.success(function(request){
                  location.reload();
              });
            }
</script>
	
</head>
<body>
	<?php		
	    if(empty($_SESSION['usuario_nombre'])) { // comprobamos que las variables de sesión estén vacías        
	?>			
	<div class="formsInicio" style="width:25%;min-width:320px;">
	    
        <form action="../controller/loginController.php?method=login" method="post" onsubmit="return validateForm()">
            <img src="../public/img/ubu.png" style="float:left;height: 50px;margin-top: -1%;">
        	<h1><?php echo(_("Acceso UBUPal"));?></h1>
        	<label id="labelLang"><?php echo(_("Idioma:"));?></label>
        	<select id="languageSelect" onchange="changeLang(this)">
        	    <option value="0"><?php echo(_("Español"));?></option>
                <option value="1"><?php echo(_("Inglés"));?></option>               
            </select> 
        	<label><?php echo(_("DNI:"));?></label>
			<input  type="text" name="usuario_nombre" id="usuario" />
        	<label ><?php echo(_("Contraseña:"));?></label>
        	<input  type="password" name="usuario_clave" id="password"/>
            <input class="buttonInicio" type="submit" name="enviar" value="<?php echo(_("Entrar"));?>" id="login" />
            <a href="register.php"><?php echo(_("Registro de alumnos"));?></a> <br />
            <a href="forgotPassword.php"><?php echo(_("¿Ha olvidado la contraseña?"));?></a>    
        </form>                   
    </div>
	<?php
	    }else {
    		if($_SESSION['usuario_tipo'] == "ADMIN"){
                header("Location: ../view/usersAdmin.php");
            }
            if($_SESSION['usuario_tipo'] == "PROFESOR"){
                header("Location: ../view/usersAdmin.php");
            }
            if($_SESSION['usuario_tipo'] == "ALUMNO"){
                header("Location: ../view/groupTeacher.php");
            }
	    }
	?>
</body>
</html>