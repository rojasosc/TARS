<?php

	include('../db.php');
	
	$student = getStudent($_POST['email']);

	echo json_encode($student,true)
?>