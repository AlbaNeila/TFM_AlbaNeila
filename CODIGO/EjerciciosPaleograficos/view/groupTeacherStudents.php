<?php
session_start();
ob_start();
$grupo=$_REQUEST['grupo'];
$idGrupo=$_REQUEST['idGrupo'];
?>
<script>
    function addEventsToImages(){
    $(window).ready(function() { 
        setTimeout(function() {
            var td;
            var img;
            var grupo;
            $('.objbox tr').each(function (index){
                 $(this).children("td").each(function (index2) {
                    if(index2 == 3){ //Imagen eliminar alumno 
                        $(this).children("img").bind('click',function($this){
                            var idAlumno = $(this).attr("id");
                             var message = $('<p />', { text: '<?php echo(_("¿Está seguro de que desea denegar el acceso al grupo a este alumno?"));?>'}),
                              ok = $('<button />', {text: 'Ok', click: function() {deleteStudent(idAlumno);}}),
                              cancel = $('<button />', {text: '<?php echo(_("Cancelar"))?>'});
                        
                            dialogue( message.add(ok).add(cancel), '<?php echo(_("Confirmación denegar acceso alumno"))?>'); 
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
    
    function deleteStudent(idAlumno){
        if(idAlumno!=""){
            var request = $.ajax({
              type: "POST",
              url: "../controller/groupController.php",
              async: false,
              data: {
                method:"deleteStudent", idAlumno: idAlumno, idGrupo:<?php echo $idGrupo;?>,
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        mygrid.clearAll();
                        mygrid.loadXML("../controller/gridControllers/gridStudents.php?idGrupo="+<?php echo $idGrupo;?>,addEventsToImages);   
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
include ('/menu/menuGroupTeacher.php');
ob_start();
?>
        <h3><?php echo(_("Grupo: ")); echo $grupo;?></h3>
        <h3><?php echo(_("Alumnos"));?></h3>
        
        <div id="gridGroups" style="width: 60%; height: 90%"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridGroups');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("<?php echo(_("Nombre"));?>, <?php echo(_("Apellidos"));?>, <?php echo(_("Email"));?>, <?php echo(_("Eliminar"));?>");
            mygrid.setInitWidths("*,*,*,100");
            mygrid.setColAlign("left,left,left,center");
            mygrid.setColTypes("ro,ro,ro,img");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,200);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("true,true,true,false");
            mygrid.setSizes();
            mygrid.setSkin("light");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridControllers/gridStudents.php?idGrupo="+<?php echo $idGrupo;?>,addEventsToImages);  
        </script>
        
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>


