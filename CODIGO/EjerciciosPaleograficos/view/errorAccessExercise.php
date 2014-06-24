<?php
session_start();
if($_SESSION['usuario_tipo'] != "ALUMNO"){
    header('Location: ../view/login.php');
}
include('../model/acceso_db.php');

ob_start();
?>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('menu/menu1.php');
include('../init.php');
ob_start();
?>
   
      
    <div id="documentGoBack" class="formulario" style="text-align: right;right:20px;margin-top: -4px;">
        <h3><a href="collectionsStudent.php"><?php echo(_("Volver"));?></a></h3>
    </div>
    <div class="formulario" style="left:25px;">
        <h1><?php echo(_("Error en el ejercicio."));?></h1>
        <p><?php echo(_("Ocurrió un error mientras se cargaba el ejercicio. Éste puede encontrarse erróneo o no contener el formato adecuado."));?></p>
        <p><?php echo(_("Por favor, contacte con el profesor responsable para solucionarlo. Disculpen las molestias."));?></p>
    </div>
   <form action="documentStudent.php" name="access" id="access" method="post" style="display:none;">
        <input type="hidden" name="coleccion" id="coleccion" value="<?php echo $nameCollection;?>"/>
        <input type="hidden" name="idColeccion"  id="idColeccion" value="<?php echo $idCollection;?>"/>            
    </form>
    
    
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


