<?php
	include('../model/acceso_db.php');
	
	$grupo = $_POST['grupo'];

	$desc = "";

						    
	
	$result = mysqli_query($GLOBALS['link'],"SELECT grupo.descripcion FROM grupo WHERE grupo.nombre='".utf8_decode($grupo)."'");
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