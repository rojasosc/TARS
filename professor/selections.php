<?php

include('../db.php');

foreach($_POST as $action){
	$IDs = explode(" ",$action);

	$status = $IDs[0];
	$studentID = $IDs[1];
	$positionID = $IDs[2];

	echo $status. " " . $studentID . " ". $positionID . " ";

	if(!!$status){

		setPositionStatus($studentID,$positionID,$status);
	}

}

header('Location: applicants.php');

