<?php
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