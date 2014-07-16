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
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="helpStudent.php" ><?php echo(_("Acerca de"));?></a></div>
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="manualStudent.php" style="font-weight: bold"><?php echo(_("Manual de usuario"));?></a></div>
    </div>
    
    <div class="formulario" style="left: 20%;width: 76%;height:100% !important;" >
           <embed src="../public/img/manualStudent.pdf" width="100%" height="100%">   </embed>  
    </div>

<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>