<?php
    //Finish the session and redirect to the login page
    
    session_start();
    if(isset($_SESSION['usuario_nombre'])) {
        session_destroy();
        header("Location: ../view/login.php");
    }else {
        header("Location: ../view/login.php");
    }
?> 