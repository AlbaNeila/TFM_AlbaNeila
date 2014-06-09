<?php
session_start();
include('../model/acceso_db.php');
$idDocument="";
if(isset( $_POST['idDocument'])){
    $idDocument = $_POST['idDocument'];
}
ob_start();
?>
<script>
    var img="";
    
    $(document).ready(function(){
        var idDocument=$('#idDocument').val();
        
        if(idDocument!=""){
            var request = $.ajax({
                  type: "POST",
                  url: "../controller/addDocumentController.php",
                  async: false,
                  data: {
                    method:"accessDoc", idDocument: idDocument
                  },
                  dataType:"json",  
                });
                request.success(function(json){
                        if($.trim(json.result) == "1"){
                            img=json.image;
                            document.getElementById('nombre').textContent =json.nombre;
                            document.getElementById('descripcion').textContent=json.descripcion;
                            document.getElementById('fecha').textContent=json.fecha;
                            document.getElementById('tipoEscritura').textContent=json.tipoEscritura;
                            $('#doc').attr('src',img);
                        }
                        else{
                            alert("error");
                        }
                });
        }
    });
</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
include ('/menu/menu1.php');
include('../init.php');
ob_start();
?>
   <input type="hidden" name="idDocument" id="idDocument" value="<?php echo $idDocument;?>"/>
   
       <div class="info" style="margin-top:2%;">
           <div style="max-width: 90%;overflow: auto;">
               <table style="float:left;margin-top:-4px;margin-left:5px;text-align: center;font-size:100%;table-layout: fixed;"  cellspacing="10">
                   <tr>
                       <td class="td_label_info"><label ><?php echo(_("Nombre"));?></label></td>
                       <td class="td_label_info"><label ><?php echo(_("Descripción"));?></label></td>
                       <td class="td_label_info"><label ><?php echo(_("Fecha"));?></label></td>
                       <td class="td_label_info"><label ><?php echo(_("Tipo escritura"));?></label></td>
                       
                   </tr>
                   <tr>
                       <td><label id="nombre"></label></td>
                       <td><label id="descripcion"></label></td>
                       <td><label id="fecha"></label></td>
                       <td style="word-break:break-all;"><label id="tipoEscritura"></label></td>
                   </tr>
               </table>
           </div>
            <h3 style="text-align: right;margin-right:5px;margin-top: -4%;"><a href="collectionsStudent.php"><?php echo(_("Volver"));?></a></h3>
       </div>
       
       <div id="contentImage" style="text-align: center;margin-top:5%;">
            <img  id="doc">
       </div>
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
?>


