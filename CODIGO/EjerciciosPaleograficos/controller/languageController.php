<?php
//Change the global language variable with the value received in the 'lang' post variable to update the language of the application.

session_start();
if(isset($_POST['lang'])){
    $lang = $_POST['lang'];
    $_SESSION['lang']=$lang;
    if($lang=="en_US"){
        echo 1;
    }else{
        echo 2;
    }  
}
?>