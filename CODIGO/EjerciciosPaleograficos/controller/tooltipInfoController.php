<?php
	include('../model/persistence/groupService.php');
	
	$grupo = $_POST['grupo'];
	$desc = "";

	$result = groupService::getDescriptionById(utf8_decode($grupo));
    if($result!=FALSE){
    	if($row=mysqli_fetch_assoc($result)){
    		$desc = $row['descripcion'];
			echo utf8_encode($desc);
    	}
	}
	else{
		echo $desc;
	} 
?>