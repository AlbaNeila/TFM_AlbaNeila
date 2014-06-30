<?php
session_start();
if($_SESSION['usuario_tipo'] != "ALUMNO"){
    header('Location: ../view/login.php');
}
ob_start();
include ('menu/menu4.php');
ob_start();
?>
    <div class="submenu">
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="helpStudent.php" style="font-weight: bold"><?php echo(_("Acerca de"));?></a></div>
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="helpStudent.php" ><?php echo(_("Manual de usuario"));?></a></div>
    </div>
    
    <div class="formulario"  >
        <table class="aboutTable">
            <tr>
                <td class="td_labelHelp" <label><?php echo(_("UBUPal"));?></label></td>
                <td><label><?php echo(_("Aplicación web para la realización de ejercicios paleográficos."));?></label></td>
                <td rowspan="8" valign="center"><img src="../public/img/ubulogo.jpg" style="margin-left: 25%;"></td>
            </tr>
            <tr>
                <td class="td_labelHelp"><label><?php echo(_("Autora"));?></label></td>
                <td><label>Alba Neila Neila</label></td>
            </tr>
             <tr>
                <td class="td_labelHelp"><label><?php echo(_("Email"));?></label></td>
                <td><label>ann0005@alu.ubu.es</label></td>
            </tr>
            <tr>
                <td class="td_labelHelp"><label><?php echo(_("Tutores"));?></label></td>
                <td><label>Álvaro Herrero Cosío y Sonia Serna Serna</label></td>
            </tr>
            <tr>
                <td class="td_labelHelp"><label><?php echo(_("Colaboradora"));?></label></td>
                <td><label>Mª Carmen Alameda Araus</label></td>
            </tr>
            <tr>
                <td class="td_labelHelp"><label><?php echo(_("Licencia"));?></label></td>
                <td><label></label></td>
            </tr>
            <tr>
                <td class="td_labelHelp"><label><?php echo(_("Versión"));?></label></td>
                <td><label></label></td>
            </tr>
            <tr>
                <td class="td_labelHelp"><label><?php echo(_("Fecha"));?></label></td>
                <td><label></label></td>
            </tr>
        </table>      
    </div>

<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>