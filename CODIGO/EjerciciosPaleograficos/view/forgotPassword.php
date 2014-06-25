<?php
ob_start();
session_start();
include('../init.php');
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

    
    function validateForm() {
        var u = check_empty($("#dniUser"),"<?php echo(_("Por favor, introduzca su DNI."));?>");

        var flag = false;
        
        if(u){
            flag= false;
        }
        else{
            var request = $.ajax({
              type: "POST",
              url: "../controller/loginController.php?method=newPassword",
              async: false,
              data: {
                dni: $("#dniUser").val()
              },
              dataType: "script",   
            });
        }
        return flag;
    }
    
</script>
    
</head>
<body>
    <div class="formsInicio" style="width: 34%;min-width: 379px;">
        <form action="login.php" method="post" id="forgotPassword" onsubmit="return validateForm()">
            <h3><?php echo(_("Nueva contraseña"));?></h3>
            <label><?php echo(_("DNI:"));?></label>
            <input  type="text" name="usuario_nombre" id="dniUser" />
            <input class="buttonInicio" type="submit" name="enviar" value="<?php echo(_("Solicitar"));?>" id="newPassword" />
            <input class="buttonInicio" type="submit" name="volver" value="<?php echo(_("Volver"));?>" style="display: inline;"/>
        </form>
    </div>
</body>
</html>