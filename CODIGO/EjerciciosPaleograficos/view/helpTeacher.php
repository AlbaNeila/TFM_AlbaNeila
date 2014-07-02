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
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="helpTeacher.php" ><?php echo(_("Manual de usuario"));?></a></div>
    </div>
    
    <div class="formulario" style="left: 27%;width: 60%" >
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
                <td><img src="../public/img/ubulogo.png" style="margin:1%;height: 120px;"></td>
            </tr>
        </table>      
    </div>
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>