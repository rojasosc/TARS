<?php
	include('../db.php');
	if(!empty($_POST)){
		$positionID = $_POST['positionID'];
		$studentID = $_POST['studentID'];
		$type = $_POST['type'];
		if($type === 'release') {
			$reasons = $_POST['reasons'];
			//TODO: Process email.
		}
		$student = User::getUserByID($studentID, STUDENT);
		$student->withdraw($positionID);
	}
?>