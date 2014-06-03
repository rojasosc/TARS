<?php

	include('../db.php');
	
	$student = getStudent($_POST['emailSearch']);
	echo json_encode($student,true);
?>