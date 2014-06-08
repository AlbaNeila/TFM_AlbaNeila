<?php
session_start();
ob_start();
include('../init.php');
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
include ('/menu/menu1.php');
ob_start();
?>
    <div class="submenu">
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="collectionsStudent.php" style="font-weight: bold"><?php echo(_("Colecciones"));?></a></div>
        <div class="submenuitem2"><img src="../public/img/menu2.png"><a><?php echo(_("Documentos"));?></a></div>
    </div>
        
        <div class="formulario">
            <h2><?php echo(_("Colecciones disponibles"));?></h2>
        </div>
              
        <div class="gridAfterForm" id="gridCollections" style="width: 85%; height: 85%;top:180px";></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridCollections');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Código colección"));?>, <?php echo(_("Nombre"));?>, <?php echo(_("Descripción"));?>, <?php echo(_("Profesor responsable"));?>, <?php echo(_("Acceder"));?>");
            mygrid.setInitWidths("100,210,*,180,90");
            mygrid.setColAlign("center,center,center,center,center");
            mygrid.setColTypes("ro,ro,ro,ro,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,400);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("false,true,true,true");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridCollectionsStudent.php");          
        </script>
        <form action="documentStudent.php" name="access" id="access" method="post" style="display:none;">
            <input type="hidden" name="coleccion" id="coleccion" value=""/>
            <input type="hidden" name="idColeccion"  id="idColeccion" value=""/>            
        </form>
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


