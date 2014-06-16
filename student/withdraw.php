<?php
	include('../db.php');
	if(!empty($_POST)){
		echo "postISSET";
		$positionID = $_POST['positionID'];
		$studentID = $_POST['studentID'];
		$student = User::getUserByID($studentID, STUDENT);
		$student->withdraw($positionID);
	}
?>