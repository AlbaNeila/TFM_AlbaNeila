<?php
	include('../model/persistence/groupService.php');
	
	$grupo = $_POST['grupo'];

	$description = groupService::getDescriptionById(utf8_decode($grupo));
    if($description!=null){
		echo utf8_encode($description);
	}
	else{
		echo "";
	} 
?>