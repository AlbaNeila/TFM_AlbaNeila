<?php



if(!isset($_SESSION['lang'])){
    $_SESSION['lang']='es_ES';
}
$LOCALE = $_SESSION['lang'];

putenv('LANG='.$LOCALE);
setlocale(LC_ALL, $LOCALE.'.UTF-8');
bindtextdomain('default', dirname(__FILE__).'/locale');
textdomain('default');
?>