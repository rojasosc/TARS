<?php
	include('../db.php');
	$office = Place::getPlaceByBuildingAndRoom($_POST['building'],$_POST['room']);	
	$officeID = $office->getPlaceID();
	$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
	Professor::insertProfessor($_POST['email'], $password_hash, $_POST['firstName'], $_POST['lastName'],
		$officeID, $_POST['officePhone'], $_POST['mobilePhone']);
?>