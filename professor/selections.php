<?php
	require_once('../db.php');
	
	$studentID = $_POST['universityID'];
	$positionID = $_POST['positionID'];
	$decision = $_POST['decision'];
	
	if($decision > 0){
		$student = User::getUserByID($studentID);
		$position = Position::getPositionByID($positionID);
		Application::setPositionStatus($student, $position, $decision);
	}

