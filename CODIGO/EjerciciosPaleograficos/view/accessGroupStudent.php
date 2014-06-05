<?php
session_start();
ob_start();
include('../init.php');
$grupo="";
$idGrupo="";
if(isset($_POST['grupo'])){
    $grupo = $_POST['grupo'];
}
if(isset($_POST['idGrupo'])){
    $idGrupo = $_POST['idGrupo'];
}
?>

    <script>
        function accessCollection(){
            var rowId = mygrid.getSelectedId();
            var idColeccion = mygrid.cellById(rowId, 0).getValue();
            var coleccion = mygrid.cellById(rowId, 1).getValue();
            
            $('#idColeccion').val(idColeccion);
            $('#coleccion').val(coleccion);
            
            $('form#access').submit();       
        }
    </script>

<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('/menu/menu2.php');
ob_start();
?>
    <div class="submenu">
        <div class="submenuitem"><a  ><?php echo(_("Grupos"));?></a></div>
        <div class="submenuitem"><a style="font-weight: bold"><?php echo(_("Colecciones"));?></a></div>
    </div>
        
        <label style="margin-left: 145px"><?php echo(_("Grupo:"));?></label>
        <label ><?php echo $grupo;?></label>
        <input type="hidden" id="idGrupo" value="<?php echo $idGrupo;?>" />
        <a href="groupStudent.php"><?php echo(_("Volver"));?></a>
              
        <div class="gridAfterForm" id="gridCollections" style="width: 85%; height: 85%;top:185px";></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridCollections');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Código colección"));?>, <?php echo(_("Nombre"));?>, <?php echo(_("Descripción"));?>, <?php echo(_("Documentos"));?>,<?php echo(_("Ejercicios"));?>, <?php echo(_("Acceder"));?>");
            mygrid.setInitWidths("100,210,*,90,90,90");
            mygrid.setColAlign("center,center,center,center,center,center");
            mygrid.setColTypes("ro,ro,ro,ro,ro,ro");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,400);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("false,true,true,false,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridAccessCollectionsStudent.php?idGroup="+<?php echo $idGrupo;?>);          
        </script>
        <form action="documentStudent.php" name="access" id="access" method="post" style="display:none;">
            <input type="hidden" name="coleccion" id="coleccion" value=""/>
            <input type="hidden" name="idColeccion"  id="idColeccion" value=""/>
            <input type="hidden" name="accessGroup"  id="accessGroup" value="true"/> 
            <input type="hidden" name="grupo" id="grupo" value="<?php echo $grupo;?>"/>
            <input type="hidden" name="idGrupo"  id="idGrupo" value="<?php echo $idGrupo;?>"/>             
        </form>
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


