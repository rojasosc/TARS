<?php
	include('../db.php');
	$courseTitle = $_POST['courseTitle'];
	$professors = Course::getCourseProfessors($courseTitle);
	echo json_encode($professors,true);
?>