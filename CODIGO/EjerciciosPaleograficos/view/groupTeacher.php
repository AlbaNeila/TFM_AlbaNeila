<?php
session_start();
ob_start();
?>
<script src="../lib/dhtmlxDataProcessor/codebase/dhtmlxdataprocessor.js"></script>
<script src="../lib/dhtmlxDataProcessor/codebase/dhtmlxdataprocessor_deprecated.js"></script>
<script>
    function validateForm() {
        var u = check_empty($("#nombregrupo"));
        var p = check_empty($("#descripciongrupo"));
        var flag = false;
        
        if(u || p){
            flag= false;
        }
        else{
           var request = $.ajax({
              type: "POST",
              url: "../controller/groupController.php",
              async: false,
              data: {
                method:"newGroup", grupo: $("#nombregrupo").val(), descripcion: $("#descripciongrupo").val()
              },
              dataType: "script",   
            });
            request.success(function(request){
                    if($.trim(request) == "1"){
                        flag= true;
                    }
                    else{
                        flag= false;
                        set_tooltip($("#nombregrupo"),"<?php echo(_("Ya existe un grupo con el mismo nombre. Por favor, introduzca un nombre de grupo diferente."));?>");
                    }
            });
        }       
        return flag;
    }


</script>
<?php
$GLOBALS['TEMPLATE']['extra_head']= ob_get_clean();
ob_start();
?>
  	<?php
  		$tipoUsuario = $_SESSION['usuario_tipo'];
		if($tipoUsuario == 'ALUMNO'){
  	?>
  		<ul id="menu">
  			<li><a href="#" style="text-decoration:underline"><?php echo(_("Colecciones"));?></a></li>
  			<li><a href="#"><?php echo(_("Grupos"));?></a></li>
  			<li><a href="#"><?php echo(_("Estadísticas"));?></a></li>
  			<li><a href="#"><?php echo(_("Ayuda"));?></a></li>
  		</ul>
  		<ul id="menu2">
  			<li><a href="../controller/logout.php">Salir</a></li>
  			<li><label><?php echo(_("Bienvenid@:"));?>  <?php echo($_SESSION['usuario_nombre']); ?></label></li>
  			<li><img src="../public/img/english.png" style="height: 25px;padding-top: 2px;padding-left: 4px;" title="<?php echo(_("Inglés"));?>" id="en_US" onclick="changeLanguage($(this).attr('id'))" /></li>
  			<li><img src="../public/img/spanish.png" style="height: 25px;padding-top: 2px;padding-right: 4px;" title="<?php echo(_("Español"));?>" id="es_ES" onclick="changeLanguage($(this).attr('id'))" /></li>  			
  		</ul> 		
  		<?php
		}elseif($tipoUsuario == 'PROFESOR'){
  		?>
  		<ul id="menu">
  			<li><a href="collectionsTeacher.php" ><?php echo(_("Colecciones"));?></a></li>
  			<li><a href="groupTeacher.php" class="active"><?php echo(_("Grupos"));?></a></li>
  			<li><a href="exercisesTeacher.php"><?php echo(_("Ejercicios"));?></a></li>
  			<li><a href="statisticsTeacher.php"><?php echo(_("Estadísticas"));?></a></li>
  			<li><a href="helpTeacher.php"><?php echo(_("Ayuda"));?></a></li>
  		</ul>
  		<ul id="menu2">
  			<li><a href="../controller/logout.php">Salir</a></li>
  			<li><label><?php echo(_("Bienvenid@:"));?>  <?php echo($_SESSION['usuario_nombre']); ?></label></li>
  			<li><img src="../public/img/english.png" style="height: 25px;padding-top: 2px;padding-left: 4px;" title="<?php echo(_("Inglés"));?>" id="en_US" onclick="changeLanguage($(this).attr('id'))" /></li>
  			<li><img src="../public/img/spanish.png" style="height: 25px;padding-top: 2px;padding-right: 4px;" title="<?php echo(_("Español"));?>" id="es_ES" onclick="changeLanguage($(this).attr('id'))" /></li>  			
  		</ul>
  		<?php
  		}else{
  		?>
  		<ul id="menu">
  			<li><a href="#" style="text-decoration:underline"><?php echo(_("Usuarios"));?></a></li>
  			<li><a href="#"><?php echo(_("Colecciones"));?></a></li>
  			<li><a href="#"><?php echo(_("Ejercicios"));?></a></li>
  			<li><a href="#"><?php echo(_("Estadísticas"));?></a></li>
  		</ul>
  		<ul id="menu2">
  			<li><a href="../controller/logout.php">Salir</a></li>
  			<li><label><?php echo(_("Bienvenid@:"));?>  <?php echo($_SESSION['usuario_nombre']); ?></label></li>
  			<li><img src="../public/img/english.png" style="height: 25px;padding-top: 2px;padding-left: 4px;" title="<?php echo(_("Inglés"));?>" id="en_US" onclick="changeLanguage($(this).attr('id'))" /></li>
  			<li><img src="../public/img/spanish.png" style="height: 25px;padding-top: 2px;padding-right: 4px;" title="<?php echo(_("Español"));?>" id="es_ES" onclick="changeLanguage($(this).attr('id'))" /></li>  			
  		</ul>
  		<?php
		}
$GLOBALS['TEMPLATE']['menu']= ob_get_clean();

ob_start();
?>
        <div class="divForm" style="width:22%;min-width:278px;" action="groupTeacher.php" method="post" onsubmit="return validateForm()">
            <form>
                <h3><?php echo(_("Añadir nuevo grupo"));?></h3>
                <label><?php echo(_("Nombre"));?></label>
                <input type="text" id="nombregrupo">
                <label><?php echo(_("Descripción"));?></label>
                <input type="text" id="descripciongrupo" />
                <input  type="submit" name="newTeacher" value="<?php echo(_("Añadir"));?>" id="newTeacher" />
            </form>
        </div>
        <div id="gridGroups" style="width: 90%; height: 90%"></div>
        <script>
            var mygrid = new dhtmlXGridObject('gridGroups');
            mygrid.setImagePath("../lib/dhtmlxGrid/codebase/imgs/");
            mygrid.setHeader("Codigo grupo, Nombre, Descripción, Nº alumnos, Solicitudes, Eliminar");
            mygrid.setInitWidths("125,*,*,125,100,100");
            mygrid.setColAlign("left,left,left,left,center,center");
            mygrid.setColTypes("ro,ed,ed,ro,ro,ro");
            mygrid.enableSmartRendering(true);
            mygrid.enableAutoHeight(true,200);
            mygrid.enableAutoWidth(true);
            mygrid.enableTooltips("false,true,false,false,false,false");
            mygrid.setSizes();
            mygrid.setSkin("light");
            mygrid.init();                  
            mygrid.loadXML("../controller/gridGroups.php");  
            mygrid.attachEvent("onEditCell", function(stage,rId,cInd,nValue,oValue){
                if (stage == 2){
                    debugger;
                    var row = new Array();
                    var cont = 0;
                    mygrid.forEachCell(rId,function(c){
                        row[cont]=c.getValue();
                        cont++;
                    });
                    
                    if(nValue == ""){
                        set_tooltip($('.cellSelected'),"<?php echo(_("No puede estar vacío."));?>");
                        return false;
                    }
                    else{
                        var request = $.ajax({
                          type: "POST",
                          url: "../controller/groupController.php",
                          async: false,
                          data: {
                            method:"checkUpdateGrid", row:JSON.stringify(row) 
                          }  
                        });
                        request.success(function(request){
                                if($.trim(request) == "1"){
                                    mygrid.cellById(rId, cInd).setValue(nValue); 
                                    mygrid.editStop();
                                }
                                else{
                                    set_tooltip($('.cellSelected'),"<?php echo(_("Ya existe un grupo con el mismo nombre. Por favor, introduzca un nombre de grupo diferente."));?>");
                                    mygrid.cells(rId,cInd).setValue(oValue);
                                    mygrid.editStop(true);
                                    return false;
                                }
                        });
                    }
                    return true;
                }
            });
        </script>
        
<?php       
$GLOBALS['TEMPLATE']['content']= ob_get_clean();
include_once('template.php');
 ?>


