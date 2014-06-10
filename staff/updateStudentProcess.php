<?php

	include('../db.php');
	
	Student::updateProfile($_POST['firstName'],$_POST['lastName'],$_POST['homePhone'],$_POST['mobilePhone'],$_POST['classYear'],$_POST['major'],$_POST['gpa'],$_POST['aboutMe']);	


?>
