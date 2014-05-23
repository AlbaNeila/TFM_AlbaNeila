<?php
session_start();
include('../model/acceso_db.php');
$coleccion="";
$idColeccion="";
if(isset($_REQUEST['coleccion'])){
$coleccion=$_REQUEST['coleccion'];
}
if(isset($_REQUEST['idColeccion'])){
$idColeccion=$_REQUEST['idColeccion'];
}
$dataCollection = "../controller/documentController.php?coleccion=$coleccion&idColeccion=$idColeccion";

ob_start();
?>
<link rel="STYLESHEET" type="text/css" href="../lib/dhtmlxCombo/codebase/dhtmlxcombo.css">

<script src="../lib/dhtmlxCombo/codebase/dhtmlxcommon.js"></script>
<script src="../lib/dhtmlxCombo/codebase/dhtmlxcombo.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_whp.js"></script>
<script src="../lib/dhtmlxCombo/codebase/ext/dhtmlxcombo_extra.js"></script>

<script>
    function validateForm() {
        var flag = true;
        var u = check_empty($("#nombredoc"));
        var p = check_empty($("#descripciondoc"));
        var t = check_empty($("#tipoesc"));
        var f = check_empty($("#fechadoc"));
        var i = check_empty($("#imagen"));
        var tr = check_empty($("#transcripcion"));
        
        if($("#transcripcion").val()!=""){
            var file = $("#transcripcion").val();
            var ext = file.split('.').pop().toLowerCase();
           if ($.inArray(ext, ['xml']) == -1) {
                set_tooltip($("#transcripcion"),"<?php echo(_("La transcripción debe ser un archivo con extensión .xml"));?>");
                flag = false;
            } 
        }
        
        if(u || p || t ||f || i || tr || !flag){
            flag= false;
        }
        return flag;
    }
    
    function addEventsToImages(){
    $(window).ready(function() { 
        setTimeout(function() {
            var td;
            var img;
            var grupo;
            $('.objbox tr').each(function (index){
                 $(this).children("td").each(function (index2) {
                    if(index2 == 6){ //Imagen eliminar documento 
                        $(this).children("img").bind('click',function($this){
                            debugger;
                            var idfila = $(this).attr("id");
                            var doc = mygrid.cellById(idfila-1,0).getAttribute("idDoc");
                             var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea eliminar el documento? Se eliminarán también los ejercicios creados a partir de él."));?>'}),
                              ok = $('<button />', {text: 'Ok', click: function() {deleteDoc(doc);}}),
                              cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});
                        
                            dialogue( message.add(ok).add(cancel), '<?php echo(_("Confirmación eliminar documento"))?>'); 
                        });
                    }
                    if(index2 == 7){ //Imagen modificar ficheros
                        $(this).children("img").bind('click',function($this){
                            var idfila = $(this).attr("id");
                            var idGrupo = mygrid.cells(idfila-1, 0).getValue();
                            var grupo = mygrid.cells(idfila-1, 1).getValue();
                            window.location.href = 'groupTeacherStudents.php?grupo='+grupo+'&idGrupo='+idGrupo;
                        });
                    }
                });
            });
        },6000);
    });
}
        function dialogue(content, title) {
        $('<div />').qtip({
            content: {
                text: content,
                title: title
            },
            position: {
                my: 'center', at: 'center',
                target: $(window)
            },
            show: {
                ready: true,
                modal: {
                    on: true,
                    blur: false
                }
            },
            hide: false,
            style: {classes: 'qtip-blue'
            },
            events: {
                render: function(event, api) {
                    $('button', api.elements.content).click(function(e) {
                        api.hide(e);
                    });
                },
                hide: function(event, api) { api.destroy(); }
            }
        });
    }
    
    function deleteDoc(doc){
        if(doc!=""){
            debugger;
            var request = $.ajax({
              type: "POST",
              url: "../controller/documentController.php",
              async: false,
              data: {
                method:"deleteDoc", idDoc: doc
              },
              dataType: "script",   
            });
            request.success(function(request){
                debugger;
                    if($.trim(request) == "1"){
                        mygrid.clearAll();
                        mygrid.loadXML("../controller/gridControllers/gridDocuments.php?idColeccion="+<?php echo $idColeccion;?>,addEventsToImages);
                    }
                    else{
                        alert("error");
                    }
            });
        }
    }

</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('/menu/menuCollectionsTeacher.php');
include('../init.php');
ob_start();
?>
       
        <h3><?php echo(_("Colección: ")).$coleccion;?></h3>
        
        <div class="divForm" style="width:22%;min-width:278px;" >
            <form method="post" enctype="multipart/form-data" id="formDoc" action="../controller/addDocumentController.php" onsubmit="return validateForm()" >
                <input type="hidden" name="coleccion" value="<?php echo $coleccion;?>">
                <input type="hidden" name="idColeccion" value="<?php echo $idColeccion;?>">
                <h3><?php echo(_("Añadir nuevo documento"));?></h3>
                <label><?php echo(_("Nombre"));?></label>
                <input type="text" id="nombredoc" name="name">
                <label><?php echo(_("Descripción"));?></label>
                <input type="text" id="descripciondoc" name="description"/>
                <label><?php echo(_("Tipo escritura"));?></label>
                <input type="text" id="tipoesc" name="type"/>
                <label><?php echo(_("Fecha"));?></label>
                <input type="text" id="fechadoc" name="date"/>
                <label><?php echo(_("Imagen"));?></label>
                <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                <input type="file" id="imagen" name="imagen"/>
                <label><?php echo(_("Transcripción"));?></label>
                <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
                <input type="file" id="transcripcion" name="transcripcion"/>
                <input  type="submit" name="newDoc" value="<?php echo(_("Añadir"));?>" id="newDoc" />
            </form>
        </div>
        
        <div id="gridDocs" style="width: 90%; height: 90%"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridDocs');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Nombre"));?>, <?php echo(_("Descripción"));?>, <?php echo(_("Tipo escritura"));?>, <?php echo(_("Fecha"));?>, <?php echo(_("Ejercicios"));?>, <?php echo(_("Ordenar"));?>, <?php echo(_("Eliminar"));?>, <?php echo(_("Modificar ficheros"));?>");
            mygrid.setInitWidths("*,*,*,*,100,100,100,120");
            mygrid.setColAlign("left,left,left,left,center,center,center,center");
            mygrid.setColTypes("ro,ed,ed,ro,ro,co,img,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,200);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("false,true,true,false,false,false,false,false");
            mygrid.setSizes();
            mygrid.setSkin("light");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridDocuments.php?idColeccion="+<?php echo $idColeccion;?>,addEventsToImages);
         </script>
         </div> 
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


