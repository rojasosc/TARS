<?php
	include('../db.php');
	
	$universityID = $_POST['universityID'];
	$positionID = $_POST['positionID'];
	$decision = $_POST['decision'];
	
	if(!!$decision){
		setPositionStatus($universityID,$positionID,$decision);
		
	}
?>