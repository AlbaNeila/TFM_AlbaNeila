<?php
session_start();
include('../model/acceso_db.php');
include('../controller/transcription.php');
if($_SESSION['usuario_tipo'] != "ALUMNO"){
    header('Location: ../view/login.php');
}

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
if(!$rectangles){
    header('Location: errorAccessExercise.php');
}
$numRec = count($rectangles);
ob_start();
?>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript" src="../lib/jquery.qtip/jquery.qtip.js"></script>

<script>
    var cont=0;
    var total=0;
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
                    $(idInput).attr('disabled','true');
                }
           }
           total=inputsVisibles.length;
        }
        
        //Objetivo
        if(tipoObjetivo == '% palabras acertadas'){
            $('#objetivo').text(valorObjetivo+'<?php echo(_(" % de palabras acertadas"))?>');
        }else{
            $('#objetivo').text(valorObjetivo+'<?php echo(_(" fallos máximos"))?>');
        }

        
        //Modo de corrección del ejercicio: 1->paso a paso 0-> al final
        if(comprobarTranscripcion==1){
            $('#correccion').text('Paso a paso');
            var message = $('<p />', { text: '<?php echo(_("El modo de corrección de este ejercicio es: PASO A PASO. Esto significa que cada fragmento de transcripción, se evaluará una vez que haya sido introducido texto en su casilla correspondiente. Si selecciona la opción Continuar comenzará el ejercicio y contabilizará como un intento de realización."));?>'}),
                          ok = $('<button />', {text: '<?php echo(_("Coninuar"))?>'}),
                          cancel = $('<button />', {text: '<?php echo(_("Salir"))?>', click: function() {exit();}});
                
            dialogue( message.add(ok).add(cancel), '<?php echo(_("INICIAR EJERCICIO"))?>');
            $('#contentTranscription input').change(function(e){
                checkInputTranscription(this);
            });
        }else{
            $('#correccion').text('Al final');
            $('#contentTranscription').append('<br><input id="checkEj" type="button" onclick="checkExercise()" value="<?php echo(_("Corregir"));?>" style="float:right;margin:1%;"></input>')
            $('#contentTranscription').click(function(){
                
            });
        }
 
    });
    
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
            style: {classes: 'qtip-ubupaleodialog'
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
    
    
    function exit(){
        $('form#access').submit();
    }
    
    
    
    function checkExercise(){
         var message = $('<p />', { text: '<?php echo(_("Si selecciona la opción Continuar se realizará la corrección del ejercicio y contabilizará un intento de realización."));?>'}),
                          ok = $('<button />', {text: '<?php echo(_("Coninuar"))?>', click: function() {okCheckExercise();}}),
                          cancel = $('<button />', {text: '<?php echo(_("Volver"))?>'});
                
        dialogue( message.add(ok).add(cancel), '<?php echo(_("CORREGIR EJERCICIO"))?>');
    }
    
    function okCheckExercise(){
        $('#contentTranscription input:text').each(function(index) {
            if(!$(this).attr('disabled')){
                $(this).attr('readonly','true');
                var idInput = $(this).attr('id');
                var idTransc ='#';
                var idTransc = idTransc + idInput.replace('input','transc');
                var valor= $(this).val();
                if($(this).val()!=$(idTransc).val()){
                  $(this).css({ "background-color": "#f2dede", "border-color": "#ebccd1", "color": "#a94442" });
                  $(this).addClass('nok');
                }else{
                  $(this).css({ "background-color": "#dff0d8", "border-color": "#d6e9c6", "color": "#3c763d" });
                  $(this).addClass('ok');
                }
            }
        });
        finishExercise();
    }
    
    function checkInputTranscription(input){
        var idInput = $(input).attr('id');
        var idTransc ='#';
        var idTransc = idTransc + idInput.replace('input','transc');
        $(input).attr('readonly','true');
        if($(input).val()!=$(idTransc).val()){
            $(input).css({ "background-color": "#f2dede", "border-color": "#ebccd1", "color": "#a94442" });
        }else{
            $(input).css({ "background-color": "#dff0d8", "border-color": "#d6e9c6", "color": "#3c763d" });
        }
        cont++;
        if(cont==(numRec-total)){
            finishExercise();
        }
    }
    
    function finishExercise(){
        var correctos=0;
        var superado=0;
        debugger;
        $('#contentTranscription input:text').each(function(index) {
            if($(this).attr("class") == "inputTransc ok"){ //Verde -> correcto
                correctos++;
            } 
        });
        if(tipoObjetivo == '% palabras acertadas'){
            var porcentaje = (100*correctos)/(numRec-total);
            if(porcentaje>=valorObjetivo){
               superado=1; 
            }
        }else{ //nº máximo de fallos
            if(((numRec-total)-correctos)<valorObjetivo){
                superado=1;
            }
        }
        
        var request = $.ajax({
                  type: "POST",
                  url: "../controller/exercisesController.php",
                  async: false,
                  data: {
                    method:"finishEj", idExercise: $('#idExercise').val(), superado:superado, idCollection:$('#idColeccion').val()
                  },
                  dataType:"script",  
                });
                request.success(function(request){
                        if($.trim(request) == "1"){
                          if(superado==1){
                                  var message = $('<p />', { text: '<?php echo(_("¡Ha finalizado el ejercicio con éxito! Si pulsa la opción Revisar podrá repasar sus respuestas. Posteriormente pordrá acceder a él sin que contabilicen intentos de realización."));?>'}),
                                  ok = $('<button />', {text: '<?php echo(_("Revisar"))?>'}),
                                  cancel = $('<button />', {text: '<?php echo(_("Volver"))?>', click: function() {exit();}});
                          }else{
                                  var message = $('<p />', { text: '<?php echo(_("Lo siento no ha superado el objetivo del ejercicio. Vuelva a intentarlo de nuevo."));?>'}),
                                  cancel = $('<button />', {text: '<?php echo(_("Volver"))?>', click: function() {exit();}});
                          }
                
            dialogue( message.add(ok).add(cancel), '<?php echo(_("EJERCICIO FINALIZADO"))?>');
                        }
                        else{
                            alert("error");
                        }
                });
    }
    
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
    
    function iluminateTransc(div){
        var idInput= '#' +$(div).attr('id') + 'input';
        if(!$(idInput).attr('disabled')){
            $(idInput).css({ "box-shadow": "0 0 7px yellow ","border-color": "#ebccd1", "outline": "none"});
            $(idInput).focus();
        }
    }
    
    function desIluminateTransc(input){
        var idInput = $(input).attr('id');
        var idTransc ='#';
        var idTransc = idTransc + idInput.replace('input','');
        $(idTransc).css({ "border": ""});
        $(input).css({ "box-shadow": "","border-color": "","outline": ""});
    }
    
    function iluminateDiv(input){
        var idInput = $(input).attr('id');
        var idTransc ='#';
        var idTransc = idTransc + idInput.replace('input','');
        $(idTransc).css({ "border":"3px dashed #AC233E","outline": "0px" });
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
        <h3><?php echo(_("Información Documento "));?></h3>
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
           <h3><?php echo(_("Información Ejercicio"));?></h3>
           <div style="overflow: auto;">
               <table style="float:left;margin-top:-4px;margin-left:5px;font-size:100%;table-layout: fixed;"  cellspacing="10">
                    <tr>
                        <td class="td_label_info"><?php echo(_("Ejercicio:"));?></td>
                        <td style="word-break:break-all;"><label id="nombreej"><label ></td>
                    </tr>
                    <tr>
                        <td class="td_label_info"><label ><?php echo(_("Objetivo:"));?></label></td>
                        <td style="word-break:break-all;"><label id="objetivo"></label></td> 
                    </tr>
                    <tr>
                        <td class="td_label_info"><label ><?php echo(_("Modo corrección:"));?></label></td>
                        <td style="word-break:break-all;"><label id="correccion"></label></td> 
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
            <div  tabindex="3" id="<?php echo $rectangle->getIdRectangle();?>"  class="<?php echo $rectangle->getClassRectangle();?>" onclick="iluminateTransc(this);" style="width:<?php echo $rectangle->getWidthRectangle();?> ;height:<?php echo $rectangle->getHeightRectangle();?>;top: <?php echo $rectangle->getTopRectangle();?>;left: <?php echo $rectangle->getLeftRectangle();?>;">        
            </div>
        <?php          
        }  
        ?>
   </div>
   
   <div id="contentTranscription" class="textUbupal" style="text-align: left;overflow:auto;height:45%;position:relative;width:98%;"  >
        <h3><?php echo(_("Transcripción:"));?></h3>
        <?php
        $line=$rectangles[0]->getLineRectangle();
        $i=1;
        ?><label><?php echo(_("Linea: ")); echo $i;?></label><?php
        //Zona transcription
        $i++;
        foreach($rectangles as $rectangle){
            $width=strlen($rectangle->getTranscriptionRectangle())*6.5;
            if($line!=$rectangle->getLineRectangle()){
            ?>
            <br>
            <label><?php echo(_("Linea: ")); echo $i;?></label>
            <input type="text" onclick="iluminateDiv(this);" onblur="desIluminateTransc(this);" id="<?php echo $rectangle->getIdRectangle();?>input" class="inputTransc" style="width:<?php echo $width;?>" />
            <input type="hidden" id="<?php echo $rectangle->getIdRectangle();?>transc" value="<?php echo $rectangle->getTranscriptionRectangle();?>"/>
            <?php
            $i++;
            }else{
            ?>
            <input type="text" onclick="iluminateDiv(this);" onblur="desIluminateTransc(this);"  id="<?php echo $rectangle->getIdRectangle();?>input" class="inputTransc" style="width:<?php echo $width;?> "/>
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


