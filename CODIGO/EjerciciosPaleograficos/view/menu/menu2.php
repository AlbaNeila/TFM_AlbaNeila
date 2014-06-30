<?php
        ob_start();
        include('../init.php');
        $tipoUsuario = $_SESSION['usuario_tipo'];
        if($tipoUsuario == 'ALUMNO'){
?>
        <ul class="menu">
            <li><a class="generalTooltip" href="groupStudent.php" style="text-decoration:underline"><?php echo(_("Grupos"));?></a></li>
            <li><a href="collectionsStudent.php" ><?php echo(_("Colecciones"));?></a></li>
            <li><a href="helpStudent.php"><?php echo(_("Ayuda"));?></a></li>
        </ul>
        <ul class="menu2">
            <li><a href="../controller/logout.php"><?php echo(_("Salir"));?></a></li>
            <li><label><?php echo(_("Bienvenid@:"));?>  <?php echo($_SESSION['usuario_nombre']); ?></label></li>
            <li><img src="../public/img/english.png" style="height: 25px;padding-top: 2px;padding-left: 4px;" title="<?php echo(_("Inglés"));?>" id="en_US" onclick="changeLanguage($(this).attr('id'))" /></li>
            <li><img src="../public/img/spanish.png" style="height: 25px;padding-top: 2px;padding-right: 4px;" title="<?php echo(_("Español"));?>" id="es_ES" onclick="changeLanguage($(this).attr('id'))" /></li>              
        </ul>       
        <?php
        }elseif($tipoUsuario == 'PROFESOR'){
        ?>
        <ul class="menu">
            <li><a class="generalTooltip" href="collectionsTeacher.php" ><?php echo(_("Colecciones"));?></a></li>
            <li><a href="groupTeacher.php" style="text-decoration: underline"><?php echo(_("Grupos"));?></a></li>
            <li><a href="exercisesTeacher.php"><?php echo(_("Ejercicios"));?></a></li>
            <li><a href="helpTeacher.php"><?php echo(_("Ayuda"));?></a></li>
        </ul>
        <ul class="menu2">
            <li><a href="../controller/logout.php"><?php echo(_("Salir"));?></a></li>
            <li><label><?php echo(_("Bienvenid@:"));?>  <?php echo($_SESSION['usuario_nombre']); ?></label></li>
            <li><img src="../public/img/english.png" style="height: 25px;padding-top: 2px;padding-left: 4px;" title="<?php echo(_("Inglés"));?>" id="en_US" onclick="changeLanguage($(this).attr('id'))" /></li>
            <li><img src="../public/img/spanish.png" style="height: 25px;padding-top: 2px;padding-right: 4px;" title="<?php echo(_("Español"));?>" id="es_ES" onclick="changeLanguage($(this).attr('id'))" /></li>              
        </ul>
        <?php
        }else{
        ?>
        <ul class="menu">
            <li><a class="generalTooltip" href="usersAdmin.php"><?php echo(_("Usuarios"));?></a></li>
            <li><a href="collectionsAdmin.php" style="text-decoration:underline"><?php echo(_("Colecciones"));?></a></li>
            <li><a href="exercisesAdmin.php"><?php echo(_("Ejercicios"));?></a></li>
        </ul>
        <ul class="menu2">
            <li><a href="../controller/logout.php"><?php echo(_("Salir"));?></a></li>
            <li><label><?php echo(_("Bienvenid@:"));?>  <?php echo($_SESSION['usuario_nombre']); ?></label></li>
            <li><img src="../public/img/english.png" style="height: 25px;padding-top: 2px;padding-left: 4px;" title="<?php echo(_("Inglés"));?>" id="en_US" onclick="changeLanguage($(this).attr('id'))" /></li>
            <li><img src="../public/img/spanish.png" style="height: 25px;padding-top: 2px;padding-right: 4px;" title="<?php echo(_("Español"));?>" id="es_ES" onclick="changeLanguage($(this).attr('id'))" /></li>              
        </ul>
<?php
        }
$GLOBALS['TEMPLATE']['menu']= ob_get_clean();
?>