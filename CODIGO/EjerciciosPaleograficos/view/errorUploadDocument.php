<?php
session_start();
include("../model/persistence/acceso_db.php");

ob_start();
?>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('menu/menu2.php');
include('../init.php');
ob_start();
?>
   
    <div class="formulario" style="left:25px;">
        <h1><?php echo(_("Error durante la subida de documentos."));?></h1>
        <p><?php echo(_("Ocurrió un error mientras se guardaban los documentos."));?></p>
        <p><?php echo(_("Inténtelo de nuevo más tarde. Disculpen las molestias."));?></p>
    </div>
    
    
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


