<?php
session_start();
include('../model/acceso_db.php');
$idExercise="";
if(isset( $_POST['idExercise'])){
    $idExercise = $_POST['idExercise'];
}
$idDocument="";
if(isset( $_POST['idDocument'])){
    $idDocument = $_POST['idDocument'];
}
$nameCollection="";
if(isset( $_POST['nameCollection'])){
    $nameCollection = $_POST['nameCollection'];
}
$idCollection="";
if(isset( $_POST['idColeccion'])){
    $idCollection = $_POST['idColeccion'];
}
ob_start();
?>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<script>
    var img="";
    var comprobarTranscripcion="";
    var tipoObjetivo="";
    var valorObjetivo="";
    var idDificultad="";
    
    $(document).ready(function(){
        var idDocument=$('#idDocument').val();
        var idExercise=$('#idExercise').val();
        debugger;
        if(idDocument!=""){
            var request = $.ajax({
                  type: "POST",
                  url: "../controller/exercisesController.php",
                  async: false,
                  data: {
                    method:"accessEj", idDocument: idDocument,idExercise:idExercise
                  },
                  dataType:"json",  
                });
                request.success(function(json){
                    debugger;
                        if($.trim(json.result) == "1"){
                            img=json.image;
                            document.getElementById('nombreej').textContent =json.nombreej;
                            document.getElementById('nombre').textContent =json.nombre;
                            document.getElementById('descripcion').textContent=json.descripcion;
                            document.getElementById('fecha').textContent=json.fecha;
                            document.getElementById('tipoEscritura').textContent=json.tipoEscritura;
                            comprobarTranscripcion=json.comprobarTranscripcion;
                            tipoObjetivo=json.tipoObjetivo;
                            valorObjetivo=json.valorObjetivo;
                            idDificultad=json.idDificultad;
                            $('#ej').attr('src',img);
                        }
                        else{
                            alert("error");
                        }
                });
        }
    });
    
     $(function() {
         var icons = {
         header: "iconClosed",    // custom icon class
         activeHeader: "iconOpen" // custom icon class
         };
         $( "#accordion" ).accordion({ icons: icons,collapsible: true, active: false});
     });
     
</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('/menu/menu1.php');
include('../init.php');
ob_start();
?>
   <input type="hidden" name="idDocument" id="idDocument" value="<?php echo $idDocument;?>"/>
   <input type="hidden" name="idExercise" id="idExercise" value="<?php echo $idExercise;?>"/>
   <div id="accordion" class="accordionStyle">
        <h3><?php echo(_("Ejercicio: "));?><label id="nombreej"><label ></h3>
        <div style="overflow: auto;">
            <table style="float:left;margin-top:-4px;margin-left:5px;font-size:100%;table-layout: fixed;"  cellspacing="10">
                   <tr>
                       <td class="td_label_info"><label ><?php echo(_("Documento:"));?></label></td>
                       <td style="word-break:break-all;"><label id="nombre"></label></td>   
                   </tr>
                   <tr>
                       <td class="td_label_info"><label ><?php echo(_("Descripción:"));?></label></td>
                       <td style="word-break:break-all;"><label id="descripcion"></label></td>   
                   </tr>
                   <tr>
                       <td class="td_label_info"><label ><?php echo(_("Fecha:"));?></label></td>
                       <td style="word-break:break-all;"><label id="fecha"></label></td>
                       
                   </tr>
                   <tr>
                       <td class="td_label_info"><label ><?php echo(_("Tipo escritura:"));?></label></td>
                       <td style="word-break:break-all;"><label id="tipoEscritura"></label></td>
                   </tr>
             <tr>
                       <td class="td_label_info"><label ><?php echo(_("Colección:"));?></label></td>
                       <td style="word-break:break-all;"><label><?php echo $nameCollection;?></label></td>
                   </tr>
               </table>
           </div>
    </div>
      
    <div id="documentGoBack" class="formulario" style="text-align: right;right:20px;margin-top: -4px;">
        <h3><a href="#" onclick="$('form#access').submit();"><?php echo(_("Volver"));?></a></h3>
    </div>
       
   <div id="contentImage" style="text-align: center;margin-top:5%;">
        <img  id="ej">
   </div>
   
   <form action="documentStudent.php" name="access" id="access" method="post" style="display:none;">
        <input type="hidden" name="coleccion" id="coleccion" value="<?php echo $nameCollection;?>"/>
        <input type="hidden" name="idColeccion"  id="idColeccion" value="<?php echo $idCollection;?>"/>            
    </form>
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


