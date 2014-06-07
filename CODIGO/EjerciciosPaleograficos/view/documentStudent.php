<?php
session_start();
include('../model/acceso_db.php');
$coleccion="";
$idColeccion="";
$accessGroup="";
if(isset( $_POST['coleccion'])){
    $coleccion = $_POST['coleccion'];
}
if(isset($_POST['idColeccion'])){
    $idColeccion = $_POST['idColeccion'];
}


if(isset($_POST['accessGroup'])){
    $accessGroup = $_POST['accessGroup'];
}
$grupo="";
$idGrupo="";
if(isset($_POST['grupo'])){
    $grupo = $_POST['grupo'];
}
if(isset($_POST['idGrupo'])){
    $idGrupo = $_POST['idGrupo'];
}

ob_start();
?>

<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('/menu/menu1.php');
include('../init.php');
ob_start();
?>
    <div class="submenu">
        <div class="submenuitem"><a ><?php echo(_("Colecciones"));?></a></div>
        <div class="submenuitem"><a style="font-weight: bold"><?php echo(_("Documentos"));?></a></div>
    </div>
        <label style="margin-left: 145px"><?php echo(_("Colección:"));?></label>
        <label><?php echo $coleccion;?></label>
        <input type="hidden" id="idColeccion" value="<?php echo $idColeccion;?>" />
        
        <?php if($accessGroup!=""){?>
        <a href="#" onclick="$('form#goGroups').submit();"><?php echo(_("Volver"));?></a>
        <form action="accessGroupStudent.php" name="goGroups" id="goGroups" method="post" style="display:none;">
            <input type="hidden" name="grupo" id="grupo" value="<?php echo $grupo;?>"/>
            <input type="hidden" name="idGrupo"  id="idGrupo" value="<?php echo $idGrupo;?>"/>            
        </form>
        <?php }else{ ?>
        <a href="collectionsStudent.php"><?php echo(_("Volver"));?></a>
        <?php } ?>
            
        <div class="gridAfterForm" id="gridDocs" style="width: 85%; height: 85%;top:185px">            
        </div>
        <script>
            var mygrid = new dhtmlXGridObject('gridDocs');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Nombre"));?>, <?php echo(_("Descripción"));?>, <?php echo(_("Tipo escritura"));?>, <?php echo(_("Fecha"));?>, <?php echo(_("Acceder"));?>");
            mygrid.setInitWidths("*,*,170,180,90");
            mygrid.setColAlign("left,left,left,left,center");
            mygrid.setColTypes("ro,ro,ro,ro,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,300);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("true,true,true,true,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridDocumentsStudent.php?idCollection="+<?php echo $idColeccion;?>);            
         </script>
         </div>
         
        <div class="gridAfterForm" id="gridEj" style="width: 85%; height: 85%;top:285px">            
        </div>
        <script>
            var mygrid = new dhtmlXGridObject('gridEj');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Nombre"));?>, <?php echo(_("Documento"));?>, <?php echo(_("Puntuación"));?>, <?php echo(_("Superado"));?>, <?php echo(_("Ejercicio"));?>");
            mygrid.setInitWidths("*,*,90,90,90");
            mygrid.setColAlign("left,left,center,center,center");
            mygrid.setColTypes("ro,ro,ro,img,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,300);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("true,true,false,false,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridExercisesStudent.php?idCollection="+<?php echo $idColeccion;?>);            
         </script>
         </div> 
         
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


