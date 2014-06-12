<?php
session_start();
include('../model/acceso_db.php');
include('../controller/transcription.php');

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
$transcription="";
if(isset( $_POST['transcription'])){
    $transcription = $_POST['transcription'];
}

$rectangles = Transcription::getTranscription($transcription);
$recPrueba= $rectangles[0]->getIdRectangle();
$recPrueba2= $rectangles[1]->getIdRectangle();
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
     
     function scrollDiv(divA, divB, divNo) {
        var div1 = $('#' + divA);
        var div2 = $('#' + divB);
        if (!div1 || !div2) return;
        var control = null;
        if (divNo == 1) control = div1;
        else if (divNo == 2) control = div2;
        if (control == null) return;
        else {
            div1.scrollLeft(control.scrollLeft());
            div2.scrollLeft(control.scrollLeft());
            div1.scrollTop(control.scrollTop());
            div2.scrollTop(control.scrollTop());
        }
    }
     
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
       
   <div id="contentImage" style="text-align: left;margin-top:2%;overflow:auto;height:55%;"  onscroll="scrollDiv('contentImage', 'contentTranscription', 1)">
        <img  id="ej">
        <?php 
        //Zona div rectangles
        foreach($rectangles as $rectangle){
            ?>
            <div id="<?php echo $rectangle->getIdRectangle();?>" class="<?php echo $rectangle->getClassRectangle();?>" style="width:<?php echo $rectangle->getWidthRectangle();?> ;height:<?php echo $rectangle->getHeightRectangle();?>;top: <?php echo $rectangle->getTopRectangle();?>;left: <?php echo $rectangle->getLeftRectangle();?>;">        
            </div>
            <?php
        }  
        ?>
   </div>
   
   <div id="contentTranscription" style="text-align: left;margin-top:2%;overflow:auto;border: 1px solid Black;"  onscroll="scrollDiv('contentImage', 'contentTranscription', 2)">
        
   </div>
   
   <form action="documentStudent.php" name="access" id="access" method="post" style="display:none;">
        <input type="hidden" name="coleccion" id="coleccion" value="<?php echo $nameCollection;?>"/>
        <input type="hidden" name="idColeccion"  id="idColeccion" value="<?php echo $idCollection;?>"/>            
    </form>
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


