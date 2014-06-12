<?php
	/* There seems to be an error with the updateProfile db.php function */
	include('../db.php');
	$student = User::getUserByEmail($_POST['email'],STUDENT);
	$student->updateProfile($_POST['firstName'],$_POST['lastName'],$_POST['mobilePhone'],$_POST['classYear'],$_POST['major'],$_POST['gpa'],$_POST['aboutMe']);	
	

?>
