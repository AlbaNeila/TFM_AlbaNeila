<?php
session_start();
ob_start();
include ('menu/menu4.php');
ob_start();
?>
    <div>
        <?php echo(_("En construcción.."));?>
    </div>
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>