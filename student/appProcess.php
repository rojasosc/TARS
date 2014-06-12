<?php
	include("../db.php");
	if(!empty($_POST)) {
		echo 'post isset';
		$pID = $_POST['positionID'];
		$comp = $_POST['compensation'];
		$qual = $_POST['qualifications'];
		$studentID = $_POST['studentID'];
		
		$student = User::getUserByID($studentID, STUDENT);
		$student->apply($pID, $comp, $qual);
	}
?>

