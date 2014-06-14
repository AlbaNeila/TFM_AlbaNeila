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
$numRec = count($rectangles);
ob_start();
?>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<script>
    var numRec=<?php echo $numRec;?>;
    var img="";
    var comprobarTranscripcion="";
    var tipoObjetivo="";
    var valorObjetivo="";
    var idDificultad="";
    var inputsVisibles = new Array();
    var Areas = new Array();
    var isIE = (navigator.userAgent.indexOf('MSIE') > -1);
    
    $(document).ready(function(){
        var idDocument=$('#idDocument').val();
        var idExercise=$('#idExercise').val();

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
        
        //Coloca los div de encima de la imagen
        initialize();
        
        //Controla el scroll de los dos divs
        $('#contentImage').scrollTop(0);
        var $divs = $('#contentImage, #contentTranscription');
        var sync = function(e){
            var $other = $divs.not(this).off('scroll'), other = $other.get(0);
            var percentage = this.scrollTop / (this.scrollHeight - this.offsetHeight);
            other.scrollTop = percentage * (other.scrollHeight - other.offsetHeight);
            // Firefox workaround. Rebinding without delay isn't enough.
            setTimeout( function(){ $other.on('scroll', sync ); },10);
        }
        $divs.on( 'scroll', sync);
        
        
        //En función de la dificultad del ejercicio, coloca las pistas aleatoriamente
        if(idDificultad==0 || idDificultad==1){ //30% inputs
            if(idDificultad==0){
                var numInptusVisibles=Math.round(numRec*0.3);
            }
            if(idDificultad==1){
                var numInptusVisibles=Math.round(numRec*0.15);
            }
           
           for(var i=0;inputsVisibles.length<numInptusVisibles ;i++){
                var num = Math.floor(Math.random() * ((numRec-1) - 0 + 1)) + 0;
                if($.inArray(num, inputsVisibles)==-1){
                    inputsVisibles.push(num);
                    var idInput='#rect'+num+'input';
                    var idInputHidden = '#rect'+num+'transc';
                    $(idInput).val($(idInputHidden).val());
                }
           }
        }
    });
    
     $(function() {
         var icons = {
         header: "iconClosed",    // custom icon class
         activeHeader: "iconOpen" // custom icon class
         };
         $( "#accordion" ).accordion({ icons: icons,collapsible: true, active: false});
     });


    
    function initialize(){
        //HeightOffset = parseInt(document.getElementById('ej').offsetTop);
        HeightOffset=0;
        ImgLeft = parseInt(document.getElementById('ej').offsetLeft);
        ImgWidth = parseInt(document.getElementById('ej').offsetWidth);
        ImgRight = ImgLeft + ImgWidth;
        ImgHeight = parseInt(document.getElementById('ej').offsetHeight);
        ImgBottom = HeightOffset + ImgHeight;
        
        var L = document.getElementById('contentImage');
        ViewWidth = parseInt(L.parentNode.offsetWidth);
        
        var NList = document.getElementsByClassName('transc');
        for (var i=0; i<NList.length; i++){
                NList[i].style.left = (parseInt(NList[i].style.left) + ImgLeft) + 'px';
                NList[i].style.top = (parseInt(NList[i].style.top) + HeightOffset) + 'px';
    //Remove non-breaking spaces which were only added for the accursed IE.
                if (isIE == false){
                    NList[i].innerHTML = '';
                }
                Areas.push(NList[i]);
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
       
   <div id="contentImage" style="text-align: left;margin-top:2%;height:55%;overflow: auto;position: relative" >
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
   
   <div id="contentTranscription" class="textUbupal" style="text-align: left;overflow:auto;height:45%;position:relative;width:98%;"  >
        <h3><?php echo(_("Transcripción:"));?></h3>
        <?php
        $line=$rectangles[0]->getLineRectangle();
        //Zona transcription
        foreach($rectangles as $rectangle){
            $width=strlen($rectangle->getTranscriptionRectangle())*10;
            if($line!=$rectangle->getLineRectangle()){
            ?>
            <br>
            <input type="text" id="<?php echo $rectangle->getIdRectangle();?>input" class="inputTransc" style="width:<?php echo $width;?> "/>
            <input type="hidden" id="<?php echo $rectangle->getIdRectangle();?>transc" value="<?php echo $rectangle->getTranscriptionRectangle();?>"/>
            <?php
            }else{
            ?>
            <input type="text" id="<?php echo $rectangle->getIdRectangle();?>input" class="inputTransc" style="width:<?php echo $width;?> "/>
            <input type="hidden" id="<?php echo $rectangle->getIdRectangle();?>transc" value="<?php echo $rectangle->getTranscriptionRectangle();?>"/>
        <?php
            }
            $line=$rectangle->getLineRectangle();          
        }  
        ?>
   </div>
   
   <form action="documentStudent.php" name="access" id="access" method="post" style="display:none;">
        <input type="hidden" name="coleccion" id="coleccion" value="<?php echo $nameCollection;?>"/>
        <input type="hidden" name="idColeccion"  id="idColeccion" value="<?php echo $idCollection;?>"/>            
    </form>
    
    
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


