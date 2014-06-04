<?php
session_start();
include('../model/acceso_db.php');
$coleccion="";
$idColeccion="";
if($coleccion==""){
    $coleccion = $_POST['coleccion'];
}
if($idColeccion==""){
    $idColeccion = $_POST['idColeccion'];
}
ob_start();
?>
<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxCombo/codebase/dhtmlxcombo.css">

<script src="../lib/dhtmlxCombo/codebase/dhtmlxcommon.js"></script>
<script src="../lib/dhtmlxCombo/codebase/dhtmlxcombo.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_whp.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_extra.js"></script>

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
        <label style="margin-left: 145px"><?php echo $coleccion;?></label>
        <input type="hidden" id="idColeccion" value="<?php echo $idColeccion;?>" />
        
        <div class="gridAfterForm" id="gridDocs" style="width: 85%; height: 85%;top:185px">
            
        </div>
        <script>
            var mygrid = new dhtmlXGridObject('gridDocs');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Nombre"));?>, <?php echo(_("Descripción"));?>, <?php echo(_("Tipo escritura"));?>, <?php echo(_("Fecha"));?>, <?php echo(_("Transcrito"));?>,<?php echo(_("Ejercicio"));?>");
            mygrid.setInitWidths("*,*,*,*,100,*");
            mygrid.setColAlign("left,left,left,left,center,center");
            mygrid.setColTypes("ro,ro,ro,ro,img,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,400);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("true,true,true,true,false,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridDocumentsStudent.php?idCollection="+<?php echo $idColeccion;?>);
            
         </script>
         </div> 
         
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


