<?php
if(!isset($_SESSION['lang'])){
    $_SESSION['lang']='es_ES';
}
$LOCALE = $_SESSION['lang'];

putenv('LANG='.$LOCALE);
setlocale(LC_ALL, $LOCALE.'.UTF-8');
bindtextdomain('default', __DIR__.'/locale');
textdomain('default');
?>