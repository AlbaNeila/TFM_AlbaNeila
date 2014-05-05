<?php
$lang = (isset($_REQUEST["lang"])) ? trim(strip_tags($_REQUEST["lang"])) : "es_ES";
define('LOCALE',$lang);
putenv('LANG='.LOCALE);
setlocale(LC_ALL, LOCALE.'.UTF-8');
bindtextdomain('default', __DIR__.'/locale');
textdomain('default');
?>