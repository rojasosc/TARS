<?php

	include('../db.php');
	
	updateStudent($_POST['firstName'],$_POST['lastName'],$_POST['email'],$_POST['homePhone'],$_POST['mobilePhone'],$_POST['classYear'],$_POST['major'],$_POST['gpa'],$_POST['aboutMe']);	


?>