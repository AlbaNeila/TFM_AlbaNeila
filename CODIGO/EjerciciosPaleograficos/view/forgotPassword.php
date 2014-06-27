<?php
ob_start();
session_start();
include('../init.php');
?>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo(_("UBUPal"));?></title>
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
            request.success(function(request){
                    if($.trim(request) == "1"){
                        location.href= 'newPasswordOk.php';
                    }
                    if($.trim(request) == "0"){
                        set_tooltip($("#dniUser"),"<?php echo(_("DNI incorrecto"));?>");
                    }
            });
        }
        return flag;
    }
    
    function goBack(){
        location.href='login.php';
    }
    
</script>
    
</head>
<body>
    <div class="formsInicio" style="width: 40%;min-width: 379px;">
        <form action="login.php" method="post" id="forgotPassword" onsubmit="return validateForm()">
            <img src="../public/img/ubu.png" style="float:left;height: 50px;margin-top: -1%;">
            <h1><?php echo(_("UBUPal Nueva contraseña"));?></h1>
            <p style="color:#006db3;"><?php echo(_("La nueva contraseña será enviada a la dirección de correo electrónico que falicitó en su registro."));?></p>
            <label><?php echo(_("Introduzca su DNI:"));?></label>
            <input  type="text" name="usuario_nombre" id="dniUser" />
            <p></p>
            <input class="buttonInicio" type="submit" name="enviar" value="<?php echo(_("Solicitar"));?>" id="newPassword" style="display:inline;"/>
            <input class="buttonInicio" type="submit" name="volver" value="<?php echo(_("Volver"));?>" onclick="goBack()" style="display:inline;"/>
        </form>
    </div>
</body>
</html>