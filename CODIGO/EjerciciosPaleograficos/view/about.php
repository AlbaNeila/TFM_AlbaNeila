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

    function goBack(){
        location.href='login.php';
    }
    
</script>
    
</head>
<body>
    <div class="formsInicio"  style="width: 45%;min-width: 569px;margin-top: 1%;text-align: center;">
        <form action="login.php" method="post" id="forgotPassword" onsubmit="return validateForm()">
            <h1 style="text-decoration: underline;"><?php echo(_("UBUPal: Acerca de"));?></h1>
            <div class="formulario" style="width: 89%" >
        <table class="aboutTable">

            <tr>
                <td> <label class="td_labelHelp"><?php echo(_("UBUPal: "));?></label><label class="td_labelHelp2"><?php echo(_("Aplicación web para la realización de ejercicios paleográficos."));?></label></td>
            </tr>
            <tr>
                <td ><label class="td_labelHelp"><?php echo(_("Autora: "));?></label><label class="td_labelHelp2">Alba Neila Neila</label></td>
            </tr>
             <tr>
                <td ><label class="td_labelHelp"><?php echo(_("Email: "));?></label><label class="td_labelHelp2">ann0005@alu.ubu.es</label></td>
            </tr>
            <tr>
                <td ><label class="td_labelHelp"><?php echo(_("Tutores: "));?></label><label class="td_labelHelp2">Álvaro Herrero Cosío y Sonia Serna Serna</label></td>
            </tr>
            <tr>
                <td ><label class="td_labelHelp"><?php echo(_("Colaboradora: "));?></label><label class="td_labelHelp2">Mª Carmen Alameda Araus</label></td>
            </tr>
            <tr>
                <td ><label class="td_labelHelp"><?php echo(_("Licencia: "));?></label><label class="td_labelHelp2"></label></td>
            </tr>
            <tr>
                <td ><label class="td_labelHelp"><?php echo(_("Versión: "));?></label><label class="td_labelHelp2">1.0</label></td>
            </tr>
            <tr>
                <td ><label class="td_labelHelp"><?php echo(_("Fecha: "));?></label><label class="td_labelHelp2"><?php echo(_("Julio de 2014"));?></label></td>
            </tr>
            <tr>
                <td><img src="../public/img/ubulogo2.png" style="margin:1%;height: 80px;"></td>
            </tr>
        </table>      
    </div>
    
        </form>
        <a href="login.php"><?php echo(_("Volver"));?></a>
    </div>
</body>
</html>