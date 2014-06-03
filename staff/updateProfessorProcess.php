<?php

	include('../db.php');
	
	updateProfessor($_POST['firstName'],$_POST['lastName'],$_POST['email'],$_POST['officePhone'],$_POST['mobilePhone']);	


?>