<?php
	include('../db.php');

	$professor = User::getUserByEmail($_POST['email'],PROFESSOR);
	$building = $_POST['building'];
	$room = $_POST['room'];
	$office = Place::getPlaceByBuildingAndRoom($building, $room);
	$officeID = $office->getPlaceID();
	$professor->updateProfile($_POST['firstName'],$_POST['lastName'],$officeID,$_POST['officePhone'],$_POST['mobilePhone']);

 ?>


