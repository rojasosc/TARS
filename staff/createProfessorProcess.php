<?php
	include('../db.php');
	$office = Place::getPlaceByBuildingAndRoom($_POST['building'],$_POST['room']);	
	$officeID = $office->getPlaceID();
	Professor::registerProfessor($_POST['email'], $password_hash, $_POST['firstName'], $_POST['lastName'],
		$officeID, $_POST['officePhone'], $_POST['mobilePhone']);
?>
