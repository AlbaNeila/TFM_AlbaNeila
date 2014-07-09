<?php
session_start();
if($_SESSION['usuario_tipo'] != "ALUMNO"){
    header('Location: ../view/login.php');
}

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
<script>

    function accessDoc(){
        var rowId = mygrid.getSelectedId();
        var doc = mygrid.cellById(rowId, 0).getAttribute('idDoc');
        var nameCol = $('#nameCol').html();
        var idCol = $('#idColeccion').val();

        $('#idDocument').val(doc);
        $('#idColeccion').val(idCol);
        $('#nameCollection').val(nameCol);
        $('form#access').submit();
    }
    
    function doEj(doc){
        var rowId = mygrid2.getSelectedId();
        var ej = mygrid2.cellById(rowId, 0).getAttribute('idEj');
        var nameCol = $('#nameCol').html();
        var idCol = $('#idColeccion').val();
        var transc = mygrid2.cellById(rowId, 0).getAttribute('transc');

        $('#type').val("do");
        $('#idDocument2').val(doc);
        $('#idExercise').val(ej);
        $('#idColeccion2').val(idCol);
        $('#nameCollection2').val(nameCol);
        $('#transcription').val(transc)
        $('form#accessEj').submit();
    }
    
    function lockEj(){
        var cell = $('td.cellselected');
        set_tooltip_left(cell,"<?php echo(_("No puede acceder a este ejercicio hasta que no haya superado el anterior."));?>")
    }
    
    function accessEj(doc){
        var rowId = mygrid2.getSelectedId();
        var ej = mygrid2.cellById(rowId, 0).getAttribute('idEj');
        var nameCol = $('#nameCol').html();
        var idCol = $('#idColeccion').val();
        var transc = mygrid2.cellById(rowId, 0).getAttribute('transc');

        $('#type').val("access");
        $('#idDocument2').val(doc);
        $('#idExercise').val(ej);
        $('#idColeccion2').val(idCol);
        $('#nameCollection2').val(nameCol);
        $('#transcription').val(transc)
        $('form#accessEj').submit();
    }
    
    onLoadFunction = function onLoadFunction(){
        if(mygrid.getRowsNum()==0){
            var label = document.createElement("label");
            label.setAttribute("class", "gridAfterForm");                           
            label.setAttribute("id", "noRecords");
            label.setAttribute("style", "width: 85%; height: 90%;top:260px;text-align: center;");                            
            $(label).text("<?php echo(_("- No se encontraron resultados -"));?>");
            document.getElementById("labelAux").appendChild(label);
        }else{
           $("#noRecords").remove();
        }
    }
    
    onLoadFunction2 = function onLoadFunction2(){
       if(mygrid2.getRowsNum()==0){
            var label2 = document.createElement("label");
            label2.setAttribute("class", "gridAfterForm");                           
            label2.setAttribute("id", "noRecords2");
            label2.setAttribute("style", "width: 85%; height: 90%;top:500px;text-align: center;");                            
            $(label2).text("<?php echo(_("- No se encontraron resultados -"));?>");
            document.getElementById("labelAux2").appendChild(label2);
        }else{
           $("#noRecords2").remove();
        }
    }
</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('menu/menu1.php');
include('../init.php');
ob_start();
?>
    <div class="submenu">
        <div class="submenuitem"><img src="../public/img/menu2.png"><a href="collectionsStudent.php" ><?php echo(_("Colecciones"));?></a></div>
        <div class="submenuitem2"><img src="../public/img/menu2.png"><a style="font-weight: bold"><?php echo(_("Documentos"));?></a></div>
    </div>
    
        <div class="formulario">
            <h2 id="nameCol"><?php echo $coleccion;?></h2>
        </div>
        <div class="formulario" style="text-align: right;width:85%;">
        <?php if($accessGroup!=""){?>
        <h3><a href="#" onclick="$('form#goGroups').submit();"><?php echo(_("Volver"));?></a></h3>
        <form action="accessGroupStudent.php" name="goGroups" id="goGroups" method="post" style="display:none;">
            <input type="hidden" name="grupo" id="grupo" value="<?php echo $grupo;?>"/>
            <input type="hidden" name="idGrupo"  id="idGrupo" value="<?php echo $idGrupo;?>"/>            
        </form>
        <?php }else{ ?>
        <h3><a href="collectionsStudent.php"><?php echo(_("Volver"));?></a></h3>
        <?php } ?>
        </div>
        
        <div class="formulario" style="top:170px;">
            <h3><?php echo(_("Documentos disponibles:"));?></h3>
        </div>
        <div class="gridAfterForm" id="gridDocs" style="width: 85%; height: 85%;top:215px">        
        </div>
        <div id="labelAux"></div>  
        <script>
            var mygrid = new dhtmlXGridObject('gridDocs');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Nombre"));?>, <?php echo(_("Descripción"));?>, <?php echo(_("Tipo escritura"));?>, <?php echo(_("Fecha"));?>, <?php echo(_("Acceder"));?>");
            mygrid.setInitWidths("*,*,170,180,90");
            mygrid.setColAlign("left,left,left,left,center");
            mygrid.setColTypes("ro,ro,ro,ro,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,170);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("true,true,true,true,false");
            mygrid.setSizes();
            mygrid.setSkin("dhx_skyblue");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridDocumentsStudent.php?idCollection="+<?php echo $idColeccion;?>,onLoadFunction);            
         </script>

        
        <div class="formulario" style="top:410px;">
            <h3><?php echo(_("Ejercicios disponibles:"));?></h3>
        </div> 
        <div class="gridAfterForm" id="gridEj" style="width: 85%; height: 85%;top:455px">   
        </div>
        <div id="labelAux2"></div> 
        <script>
            var mygrid2 = new dhtmlXGridObject('gridEj');
            mygrid2.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid2.setHeader("<?php echo(_("Nombre"));?>, <?php echo(_("Documento"));?>,<?php echo(_("Superado"));?>, <?php echo(_("Ejercicio"));?>");
            mygrid2.setInitWidths("*,*,90,90");
            mygrid2.setColAlign("left,left,center,center");
            mygrid2.setColTypes("ro,ro,img,img");
            mygrid2.enableSmartRendering(true);
            mygrid2.enableAutoHeight(true,170);
            mygrid2.enableAutoWidth(true);
            mygrid2.enableTooltips("true,true,false,false");
            mygrid2.setSizes();
            mygrid2.setSkin("dhx_skyblue");
            mygrid2.init();                  
            mygrid2.loadXML("../controller/gridControllers/gridExercisesStudent.php?idCollection="+<?php echo $idColeccion;?>,onLoadFunction2);            
         </script>
        
        <form action="accessDocument.php" name="access" id="access" method="post" style="display:none;">
            <input type="hidden" name="idDocument"  id="idDocument" value=""/>
            <input type="hidden" name="idColeccion"  id="idColeccion" value="<?php echo $idColeccion;?>"/> 
            <input type="hidden" name="nameCollection"  id="nameCollection" value=""/>            
        </form>
        
        <form action="accessExercise.php" name="accessEj" id="accessEj" method="post" style="display:none;">
            <input type="hidden" name="type"  id="type" value=""/>
            <input type="hidden" name="idDocument"  id="idDocument2" value=""/>
            <input type="hidden" name="idExercise"  id="idExercise" value=""/>
            <input type="hidden" name="idColeccion"  id="idColeccion2" value="<?php echo $idColeccion;?>"/> 
            <input type="hidden" name="nameCollection"  id="nameCollection2" value=""/> 
            <input type="hidden" name="transcription"  id="transcription" value=""/>            
        </form>
         
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


