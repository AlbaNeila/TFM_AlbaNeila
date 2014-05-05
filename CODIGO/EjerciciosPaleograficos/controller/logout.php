<?php
    session_start();

    // comprobamos que se haya iniciado la sesión
    if(isset($_SESSION['usuario_nombre'])) {
        session_destroy();
        header("Location: ../view/login.php");
    }else {
        header("Location: ../view/login.php");
    }
?> 