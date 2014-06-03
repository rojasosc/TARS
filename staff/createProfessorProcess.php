<?php

	include('../db.php');
	
	
	$office = getOffice($_POST['building'],$_POST['room']);
	
	$officeID = $office['placeID'];
	
	registerProfessor($officeID,$_POST['firstName'],$_POST['lastName'],$_POST['email'],$_POST['password'],$_POST['officePhone'],$_POST['mobilePhone']);
		
	echo "<p> Professor ". $_POST['lastName'] . "'s account " . "was successfully created.</p>";

?>