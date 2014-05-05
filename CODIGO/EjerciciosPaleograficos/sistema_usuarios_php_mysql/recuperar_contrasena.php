<?php
    include('acceso_db.php'); // incluímos los datos de acceso a la BD
    require("../class.phpmailer.php");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <?php
        if(isset($_POST['enviar'])) { // comprobamos que se han enviado los datos del formulario
            if(empty($_POST['usuario_nombre'])) {
                echo "No ha ingresado el usuario. <a href='javascript:history.back();'>Reintentar</a>";
            }else {
                $usuario_nombre = mysql_real_escape_string($_POST['usuario_nombre']);
                $usuario_nombre = trim($usuario_nombre);
                //$sql = mysql_query("SELECT usuario.usuario, usuario.password, usuario.email FROM usuario WHERE usuario.usuario='".$usuario_nombre."'");
				$result = mysqli_query($GLOBALS['link'],"SELECT  usuario.usuario, usuario.password, usuario.email FROM usuario WHERE usuario.usuario= '".$usuario_nombre."' ");
				if($result==FALSE){
					echo "El usuario <strong>".$usuario_nombre."</strong> no está registrado. <a href='javascript:history.back();'>Reintentar</a>";
				}
				else{
	                if($row = mysqli_fetch_assoc($result)) {
							                	
						$mail = new PHPMailer();
						$mail->IsSMTP();
						$mail->Host = "localhost";
						$mail->From = "albis_n@hotmail.com";
						$mail->FromName = "UBUPaleo";
						 
						$numTotal=1; //número de correos diferentes a enviar
						$flag=0; //bandera
						$direcciones ="albis_n@hotmail.com";
						$tabla=explode(" ", $direcciones); 
						while($numTotal>0){
						  $mail->AddAddress($tabla[$flag]);
						  $mail->Subject = "Asunto";
						  $mail->Body = 'Texto';
						  $mail->WordWrap = 1200;
						  if(!$mail->Send())
						    echo 'Se ha producido el siguiente error: ' . $mail->ErrorInfo;
						  else
						    echo 'El correo electrónico se ha enviado correctamente.';
						  $numTotal--;
						  $flag++;
						  $mail->ClearAddresses();
						}
	                    /*
	                    $num_caracteres = "10"; // asignamos el número de caracteres que va a tener la nueva contraseña
	                    $nueva_clave = substr(md5(rand()),0,$num_caracteres); // generamos una nueva contraseña de forma aleatoria
	                    $usuario_nombre = $row['usuario'];
	                    $usuario_clave = $nueva_clave; // la nueva contraseña que se enviará por correo al usuario
	                    $usuario_clave2 = md5($usuario_clave); // encriptamos la nueva contraseña para guardarla en la BD
	                    $usuario_email = $row['email'];
	                    // actualizamos los datos (contraseña) del usuario que solicitó su contraseña
	                    mysqli_query($GLOBALS['link'],"UPDATE usuario SET usuario.password='".$usuario_clave2."' WHERE usuario.usuario='".$usuario_nombre."'");
	                    // Enviamos por email la nueva contraseña
	                    $remite_nombre = "UBUPaleo"; // Tu nombre o el de tu página
	                    $remite_email = "albaneilaneila@gmail.com"; // tu correo
	                    $asunto = "Recuperacion de contrasena"; // Asunto (se puede cambiar)
	                    $mensaje = "Se ha generado una nueva contraseña para el usuario <strong>".$usuario_nombre."</strong>. La nueva contraseña es: <strong>".$usuario_clave."</strong>.";
	                    $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	                    $cabeceras .= "From: ".$remite_nombre." <".$remite_email.">\r\n";
	                    $enviar_email = mail('albis_n@hotmail.com', 'test email', 'this is a test');*/
	                    if($enviar_email) {
	                        echo "La nueva contraseña ha sido enviada al email asociado al usuario ".$usuario_nombre.".";
	                    }else {
	                        echo "No se ha podido enviar el email. <a href='javascript:history.back();'>Reintentar</a>";
	                    }
	                }
                }
            }
        }else {
    ?>
        <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
            <label>Usuario:</label><br />
            <input type="text" name="usuario_nombre" /><br />
            <input type="submit" name="enviar" value="Enviar" />
        </form>
    <?php
        }
    ?> 
</body>
</html>