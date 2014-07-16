<?php
session_start();
if($_SESSION['usuario_tipo'] != "PROFESOR"){
    header('Location: ../view/login.php');
}
ob_start();
include ('menu/menu5.php');
ob_start();
?>
    <div class="submenu">
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="helpTeacher.php" style="font-weight: bold"><?php echo(_("Acerca de"));?></a></div>
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="manualTeacher.php" ><?php echo(_("Manual de usuario"));?></a></div>
    </div>
    
    <div class="formulario formsAdd" style="left: 31%;width: 42%;height:540px !important;" >
        <table class="aboutTable" style="margin-left: 0% !important;">
            <tr>
                <td><label class="td_labelHelp2" style="font-size: 130%;"><?php echo(_("Aplicación web para la realización de ejercicios paleográficos."));?></label></td>
            </tr>
            <tr>
                <td ><label class="td_labelHelp"><?php echo(_("Autora: "));?></label></td></tr><tr><td><label class="td_labelHelp2">Alba Neila Neila</label></td>
            </tr>
            <tr>
                <td ><label class="td_labelHelp"><?php echo(_("Tutores: "));?></label></td></tr><tr><td><label class="td_labelHelp2">Álvaro Herrero Cosío y Sonia Serna Serna</label></td>
            </tr>
            <tr>
                <td ><label class="td_labelHelp"><?php echo(_("Colaboradora: "));?></label></td></tr><tr><td><label class="td_labelHelp2">Mª Carmen Alameda Araus</label></td>
            </tr>
            <tr>
                <td ><label class="td_labelHelp"><?php echo(_("Licencia: "));?></label></td></tr><tr><td><a target="_blank" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img src="../public/img/license.png" style="margin-top:2%;"></a></td>
            </tr>
            <tr>
                <td ><label class="td_labelHelp"><?php echo(_("Versión: "));?></label></td></tr><tr><td><label class="td_labelHelp2">1.0<?php echo(_(" - Julio de 2014"));?></label></td>
            </tr>
            <tr>
                <td><a target="_blank" href="http://wwww.ubu.es/"><img src="../public/img/ubulogo2.png" style="margin:1%;height: 100px;"></a></td>
            </tr>
        </table>      
    </div>
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>